<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\Controller;
use App\Http\Resources\Admin\RegionResource;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regions = Region::all();

        return $this->sendResponse(RegionResource::collection($regions), 'Regions retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('create', Region::class)) {
            return $this->sendError('You cannot create region', [], 403);
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        $region = Region::Create($input);

        return $this->sendResponse(new RegionResource($region), 'Region created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $region = Region::find($id);

        if (is_null($region)) {
            return $this->sendError('Region not found.');
        }

        return $this->sendResponse(new RegionResource($region), 'Region retrieved successfully.');
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
        $region = Region::find($id);

        if (is_null($region)) {
            return $this->sendError('Region not found.');
        }

        if ($request->user()->cannot('update', $region)) {
            return $this->sendError('You cannot update region', [], 403);
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        $region->update($input);

        return $this->sendResponse(new RegionResource($region), 'Region updated successfully.');
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
        $region = Region::find($id);

        if (is_null($region)) {
            return $this->sendError('Region not found.');
        }

        if ($request->user()->cannot('delete', $region)) {
            return $this->sendError('You cannot delete region', [], 403);
        }

        $buildingsWithThisRegion = $region->buildings()->getResults();
        if (count($buildingsWithThisRegion) > 0) {
            return $this->sendError('You cannot delete region because it is associated with buildings.');
        }

        $region->delete();

        return $this->sendResponse(new RegionResource($region), 'Region deleted successfully.');
    }
}
