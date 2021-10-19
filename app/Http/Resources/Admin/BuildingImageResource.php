<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class BuildingImageResource extends JsonResource
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
            'filename' => asset('api/building/image/' . $this->filename),
            'user_filename' => $this->user_filename,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
