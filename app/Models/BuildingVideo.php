<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;

class BuildingVideo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['filename', 'user_filename', 'building_id'];

    /**
     * Get the building that owns the building video.
     */
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Delete the model from the database.
     *
     * @return bool|null
     *
     * @throws \LogicException
     */
    public function deleteVideo()
    {
        $storage = Storage::disk('public');
        $path = 'files/building/video/' . $this->filename;
        $storage->delete($path);

        return parent::delete();
    }
}
