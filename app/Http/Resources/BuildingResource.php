<?php

namespace App\Http\Resources;

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
            'type' => $this->buildingType->title,
            'region' => new RegionResource($this->region),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'participant' => $this->participant,
            'description' => $this->description,
            'main_image' => $this->mainImage ? asset('api/building/image/' . $this->mainImage->filename) : null,
            'before_image' => $this->beforeImage ? asset('api/building/image/' . $this->beforeImage->filename) : null,
            'after_image' => $this->afterImage ? asset('api/building/image/' . $this->afterImage->filename) : null,
            'another_images' => $this->anotherImages->map(function ($image) {
                return [
                    'id' => $image->id,
                    'filename' => asset('api/building/image/' . $image->filename)
                ];
            })->all(),
            'video' => $this->video ? asset('api/building/video/' . $this->video->filename) : null,
            'audio' => $this->audio ? asset('api/building/audio/' . $this->audio->filename) : null,
        ];
    }
}
