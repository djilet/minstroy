<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\BuildingResource;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'region_id' => ['array'],
            'participant' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        $query = Building::select('id', 'title', 'description', 'latitude', 'longitude')->where('active', 1);

        if ($request->has('region_id')) {
            $query->whereIn('region_id', $request->get('region_id'));
        }

        if ($request->has('participant')) {
            $query->where('participant', $request->get('participant'));
        }

        $buildings = $query->withPostponed()->get()->collect();

        return $this->sendResponse($buildings, 'Buildings retrieved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $building = Building::withAnyStatus()->where('id', $id)->first();

        if (is_null($building)) {
            return $this->sendError('Building not found.');
        }

        if ($building->active === false) {
            return $this->sendError('Building is inactive.');
        }

        if (!$building->isApproved() && !$building->isPostponed()) {
            return $this->sendError('Building not approved.');
        }

        return $this->sendResponse(new BuildingResource($building), 'Building retrieved successfully.');
    }
}
