<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\RegionResource;
use App\Models\Region;

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
}
