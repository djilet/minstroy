<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\BuildingTypeResource;
use App\Http\Resources\RegionResource;
use App\Models\Building;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'address' => $this->address,
            'type' => new BuildingTypeResource($this->buildingType),
            'region' => new RegionResource($this->region),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'participant' => $this->participant,
            'active' => $this->active,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'main_image' => new BuildingImageResource($this->mainImage),
            'before_image' => new BuildingImageResource($this->beforeImage),
            'after_image' => new BuildingImageResource($this->afterImage),
            'another_images' => BuildingImageResource::collection($this->anotherImages),
            'video' => new BuildingVideoResource($this->video),
            'audio' => new BuildingAudioResource($this->audio),
            'moderation_status' => Building::API_STATUSES[$this->moderation_status],
            'moderated_at' => $this->moderated_at,
            'moderated_by' => $this->moderated_by,
            'new_values' => $this->isApproved() || $this->isPostponed() ? $this->getNewValues() : [],
        ];
    }

    public static function collection($resource)
    {
        $resource->pagination = [
            'total' => $resource->total(),
            'count' => $resource->count(),
            'per_page' => $resource->perPage(),
            'current_page' => $resource->currentPage(),
            'total_pages' => $resource->lastPage()
        ];

        return parent::collection($resource);
    }
}
