<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\Controller;
use App\Http\Resources\Admin\BuildingTypeResource;
use App\Models\BuildingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BuildingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buildingTypes = BuildingType::all();

        return $this->sendResponse(BuildingTypeResource::collection($buildingTypes), 'Building types retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('create', BuildingType::class)) {
            return $this->sendError('You cannot create building type', [], 403);
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        $buildingType = BuildingType::Create($input);

        return $this->sendResponse(new BuildingTypeResource($buildingType), 'Building type created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $buildingType = BuildingType::find($id);

        if (is_null($buildingType)) {
            return $this->sendError('Building type not found.');
        }

        return $this->sendResponse(new BuildingTypeResource($buildingType), 'Building type retrieved successfully.');
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
        $buildingType = BuildingType::find($id);

        if (is_null($buildingType)) {
            return $this->sendError('Building type not found.');
        }

        if ($request->user()->cannot('update', $buildingType)) {
            return $this->sendError('You cannot update this building type', [], 403);
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        $buildingType->update($input);

        return $this->sendResponse(new BuildingTypeResource($buildingType), 'Building type updated successfully.');
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
        $buildingType = BuildingType::find($id);

        if ($request->user()->cannot('update', $buildingType)) {
            return $this->sendError('You cannot delete this building type', [], 403);
        }

        if (is_null($buildingType)) {
            return $this->sendError('Building type not found.');
        }

        $buildingsWithThisType = $buildingType->buildings()->getResults();
        if (count($buildingsWithThisType) > 0) {
            return $this->sendError('You cannot delete building type because it is associated with buildings.');
        }

        $buildingType->delete();

        return $this->sendResponse(new BuildingTypeResource($buildingType), 'Building type deleted successfully.');
    }
}
