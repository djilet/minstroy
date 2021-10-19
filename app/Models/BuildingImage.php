<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BuildingImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['filename', 'user_filename', 'building_id', 'type', 'moderation_status'];

    /**
     * Building image types
     */
    public const TYPE_MAIN = 1;
    public const TYPE_BEFORE = 2;
    public const TYPE_AFTER = 3;
    public const TYPE_ANOTHER = 4;

    public const TYPES = [
        self::TYPE_MAIN => 'main_image',
        self::TYPE_BEFORE => 'before_image',
        self::TYPE_AFTER => 'after_image',
        self::TYPE_ANOTHER => 'another_images',
    ];

    /**
     * Get the building that owns the building image.
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
    public function deleteImage()
    {
        $storage = Storage::disk('public');
        $path = 'files/building/image/' . $this->filename;
        $storage->delete($path);

        return parent::delete();
    }
}
