<?php

namespace App\Http\Controllers\API\Admin;

use App\Enum\AdminRole;
use App\Http\Controllers\API\Controller;
use App\Http\Resources\Admin\BuildingResource;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        $validator = Validator::make($filter, [
            'moderation_status' => 'array',
            'moderation_status.*' => 'string|in:' . implode(',', Building::API_STATUSES),
            'building_title' => 'string',
            'page' => 'integer',
            'per_page' => 'integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        if ($request->has('moderation_status')) {
            $buildings = collect();
            foreach ($request->get('moderation_status') as $status) {
                $buildings = $buildings->merge(Building::getBuildingsByApiStatus($status));
            }
        } else {
            $buildings = Building::withAnyStatus()->get();
        }

        if ($request->has('building_title') ) {
            $searchTitle = mb_strtolower($request->get('building_title'));
            if (strlen($searchTitle) > 0) {
                $buildings = $buildings->filter(function ($building) use ($searchTitle) {
                    return strpos(mb_strtolower($building->title), $searchTitle) !== false;
                });
            }
        }

        if ($request->user()->role === AdminRole::USER) {
            $userId = $request->user()->id;
            $buildings = $buildings->filter(function ($building) use ($userId) {
                return $building->getCreatorId() === $userId;
            });
        }

        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $buildings = $buildings->paginate($perPage, $page);

        return $this->sendResponse(BuildingResource::collection($buildings), 'Buildings retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'building_type_id' => 'required|integer|exists:building_types,id',
            'region_id' => 'required|integer|exists:regions,id',
            'latitude' => 'required|string|max:100',
            'longitude' => 'required|string|max:100',
            'participant' => 'required|boolean',
            'active' => 'boolean',
            'description' => 'string',
            'main_image' => 'string',
            'before_image' => 'string',
            'after_image' => 'string',
            'another_images' => 'array',
            'another_images.*' => 'string',
            'video' => 'string',
            'audio' => 'string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        $building = Building::Create($request->all());

        //Update building moderation status
        if ($request->user()->can('approve', Building::class)) {
            $building->markApproved();
        } elseif (!$building->isPending()) {
            $building->markPending();
        }

        $errors = $building->saveFiles($request);

        $updatedBuilding = Building::withAnyStatus()->where('id', $building->id)->first();

        if (count($errors) > 0) {
            return $this->sendError('Building created with errors: file does not exist', [
                'filename' => $errors,
                'building' => new BuildingResource($updatedBuilding),
            ]);
        }

        return $this->sendResponse(new BuildingResource($updatedBuilding), 'Building created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $building = Building::withAnyStatus()->where('id', $id)->first();

        if (is_null($building)) {
            return $this->sendError('Building not found.');
        }

        if ($request->user()->cannot('view', $building)) {
            return $this->sendError('You cannot view this building', [], 403);
        }

        return $this->sendResponse(new BuildingResource($building), 'Building retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $building = Building::withAnyStatus()->where('id', $id)->first();

        if (is_null($building)) {
            return $this->sendError('Building not found.');
        }

        if ($request->user()->cannot('update', $building)) {
            return $this->sendError('You cannot update this building', [], 403);
        }


        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'address' => 'string|max:255',
            'building_type_id' => 'integer|exists:building_types,id',
            'region_id' => 'integer|exists:regions,id',
            'latitude' => 'string|max:100',
            'longitude' => 'string|max:100',
            'participant' => 'boolean',
            'active' => 'boolean',
            'description' => 'string',
            'main_image' => 'string|nullable',
            'before_image' => 'string|nullable',
            'after_image' => 'string|nullable',
            'another_images' => 'array',
            'another_images.*.id' => 'integer',
            'another_images.*.filename' => 'string',
            'video' => 'string|nullable',
            'audio' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        if ($request->user()->role === AdminRole::USER && ($building->isApproved() || $building->isPostponed())) {
            $buildingBeforeAttributes = $building->getAttributes();

            $errors = $building->updateFiles($request, true);

            // update and return old building for auditing
            $building->update($request->all());
            Building::disableAuditing();
            $building->update($buildingBeforeAttributes);
            Building::enableAuditing();

            $building->markPostponed();
        } else {
            $building->update($request->all());
            $errors = $building->updateFiles($request);
        }


        if ($request->user()->role === AdminRole::USER && $building->isRejected()) {
            $building->markPending();
        }

        if (count($errors) > 0) {
            return $this->sendError('Building updated with errors: file does not exist', [
                'filename' => $errors,
                'building' => new BuildingResource($building),
            ]);
        }
        return $this->sendResponse(new BuildingResource($building), 'Building updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $building = Building::withAnyStatus()->where('id', $id)->first();

        if (is_null($building)) {
            return $this->sendError('Building not found.');
        }

        if ($request->user()->cannot('delete', $building)) {
            return $this->sendError('You cannot delete this building', [], 403);
        }

        $building->deleteFiles();
        $building->delete();

        return $this->sendResponse(new BuildingResource($building), 'Building deleted successfully.');
    }

    /**
     * Approve building by admin
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        $building = Building::withAnyStatus()->where('id', $id)->first();

        if (is_null($building)) {
            return $this->sendError('Building not found.');
        }

        if ($request->user()->cannot('approve', Building::class)) {
            return $this->sendError('You cannot approve this building', [], 403);
        }

        if ($building->isApproved() || $building->isPostponed()) {
            $newValues = $building->getNewValues();
            if (count($newValues) > 0) {
                $building->update($building->getNewValues());
                $building->approveFiles();
            }
        }

        $building->markApproved();

        return $this->sendResponse(new BuildingResource($building), 'Building approved successfully.');
    }

    /**
     * Reject building by admin
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, $id)
    {
        $building = Building::withAnyStatus()->where('id', $id)->first();

        if (is_null($building)) {
            return $this->sendError('Building not found.');
        }

        if ($request->user()->cannot('reject', Building::class)) {
            return $this->sendError('You cannot reject this building', [], 403);
        }

        if ($building->isPostponed()) {
            $building->markApproved();
        } else {
            $building->markRejected();
        }

        return $this->sendResponse(new BuildingResource($building), 'Building rejected successfully.');
    }
}
