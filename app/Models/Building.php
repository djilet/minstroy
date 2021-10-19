<?php

namespace App\Models;

use App\Enum\AdminRole;
use App\Http\Resources\Admin\BuildingAudioResource;
use App\Http\Resources\Admin\BuildingImageResource;
use App\Http\Resources\Admin\BuildingVideoResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use OwenIt\Auditing\Contracts\Auditable;
use Hootlex\Moderation\Moderatable;


class Building extends Model implements Auditable
{
    use Moderatable;
    use \OwenIt\Auditing\Auditable;

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
        'moderation_status',
        'moderated_at',
        'moderated_by',
    ];

    /**
     * Moderation api statuses
     */
    public const API_STATUSES = [
        0 => 'pending',
        1 => 'approved',
        2 => 'rejected',
        // change postponed status (from library) to custom status - approved building with changed fields
        3 => 'approved_with_pending'
    ];

    /**
     * Building files moderation statuses
     */
    public const PENDING_FILE = 0;
    public const APPROVED_FILE = 1;

    protected $casts = [
        'participant' => 'boolean',
        'active' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'address',
        'building_type_id',
        'region_id',
        'latitude',
        'longitude',
        'participant',
        'active',
        'description'
    ];

    /**
     * Get the region associated with the building.
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Get the building type associated with the building.
     */
    public function buildingType()
    {
        return $this->belongsTo(BuildingType::class);
    }

    /**
     * Get another images for the building.
     */
    public function images($approved = true)
    {
        return $this->hasMany(BuildingImage::class)
            ->where('moderation_status', (int)$approved);
    }

    /**
     * Get the main image for the building.
     */
    public function mainImage($approved = true)
    {
        return $this->hasOne(BuildingImage::class)
            ->where('type', BuildingImage::TYPE_MAIN)
            ->where('moderation_status', (int)$approved);
    }

    /**
     * Get the "before" image for the building.
     */
    public function beforeImage($approved = true)
    {
        return $this->hasOne(BuildingImage::class)
            ->where('type', BuildingImage::TYPE_BEFORE)
            ->where('moderation_status', (int)$approved);
    }

    /**
     * Get the "after" image for the building.
     */
    public function afterImage($approved = true)
    {
        return $this->hasOne(BuildingImage::class)
            ->where('type', BuildingImage::TYPE_AFTER)
            ->where('moderation_status', (int)$approved);
    }

    /**
     * Get another images for the building.
     */
    public function anotherImages($approved = true)
    {
        return $this->hasMany(BuildingImage::class)
            ->where('type', BuildingImage::TYPE_ANOTHER)
            ->where('moderation_status', (int)$approved);
    }

    /**
     * Get the video for the building.
     */
    public function video($approved = true)
    {
        return $this->hasOne(BuildingVideo::class)
            ->where('moderation_status', (int)$approved);
    }

    /**
     * Get the audio for the building.
     */
    public function audio($approved = true)
    {
        return $this->hasOne(BuildingAudio::class)
            ->where('moderation_status', (int)$approved);
    }

    /**
     * Save building images, video and audio
     *
     * @param Request $request
     * @param bool $approved
     * @return array
     */
    public function saveFiles(Request $request, $approved = true)
    {
        $savingFilesErrors = [];

        $images = $request->only(['main_image', 'before_image', 'after_image']);
        foreach ($images as $key => $filename) {
            $userFilename = TemporaryFile::moveToPermanence($filename, 'image');
            if ($userFilename) {
                $buildingImage = new BuildingImage();
                $buildingImage->filename = $filename;
                $buildingImage->user_filename = $userFilename;
                $buildingImage->type = array_search($key, BuildingImage::TYPES);
                $buildingImage->moderation_status = (int)$approved;
                $this->images()->save($buildingImage);
            } else {
                $savingFilesErrors[] = $filename;
            }
        }

        $anotherImages = $request->get('another_images');
        if ($anotherImages) {
            foreach ($anotherImages as $filename) {
                $userFilename = TemporaryFile::moveToPermanence($filename, 'image');
                if ($userFilename) {
                    $buildingImage = new BuildingImage();
                    $buildingImage->filename = $filename;
                    $buildingImage->user_filename = $userFilename;
                    $buildingImage->type = BuildingImage::TYPE_ANOTHER;
                    $buildingImage->moderation_status = (int)$approved;
                    $this->images()->save($buildingImage);
                } else {
                    $savingFilesErrors[] = $filename;
                }
            }
        }

        $video = $request->get('video');
        if ($video) {
            $userFilename = TemporaryFile::moveToPermanence($video, 'video');
            if ($userFilename) {
                $buildingVideo = new BuildingVideo();
                $buildingVideo->filename = $video;
                $buildingVideo->user_filename = $userFilename;
                $buildingVideo->moderation_status = (int)$approved;
                $this->video()->save($buildingVideo);
            } else {
                $savingFilesErrors[] = $video;
            }
        }

        $audio = $request->get('audio');
        if ($audio) {
            $userFilename = TemporaryFile::moveToPermanence($audio, 'audio');
            if ($userFilename) {
                $buildingAudio = new BuildingAudio();
                $buildingAudio->filename = $audio;
                $buildingAudio->user_filename = $userFilename;
                $buildingAudio->moderation_status = (int)$approved;
                $this->audio()->save($buildingAudio);
            } else {
                $savingFilesErrors[] = $audio;
            }
        }

        return $savingFilesErrors;
    }

    /**
     * Update building images, video and audio
     *
     * @param Request $request
     * @param bool $approved
     * @return array
     */
    public function updateFiles(Request $request, $approved = true)
    {
        //Update images
        $savingFilesErrors = [];

        if ($request->has('another_images')) {
            if (count($request->get('another_images')) == 0) {
                $anotherImages = $this->anotherImages($approved)->getResults();
                foreach ($anotherImages as $image) {
                    $image->deleteImage();
                }
            }
        }

        if ($request->has('another_images') && count($request->get('another_images')) > 0) {
            $imageIdsFromRequest = [];
            foreach ($request->get('another_images') as $image) {
                if (isset($image['id'])) {
                    $imageIdsFromRequest[] = $image['id'];
                }
            }

            $anotherImages = $this->anotherImages($approved)->getResults();
            foreach ($anotherImages as $image) {
                if (!in_array($image->id, $imageIdsFromRequest)) {
                    $image->deleteImage();
                }
            }
            foreach ($request->get('another_images') as $image) {
                if (!isset($image['id']) && isset($image['filename'])) {
                    $userFilename = TemporaryFile::moveToPermanence($image['filename'], 'image');
                    if ($userFilename) {
                        $buildingImage = new BuildingImage();
                        $buildingImage->filename = $image['filename'];
                        $buildingImage->user_filename = $userFilename;
                        $buildingImage->type = BuildingImage::TYPE_ANOTHER;
                        $buildingImage->moderation_status = (int)$approved;
                        $this->images()->save($buildingImage);
                    } else {
                        $savingFilesErrors[] = $image['filename'];
                    }
                }
            }
        }
        if ($request->has('main_image')) {
            $mainImage = $this->mainImage($approved)->getResults();
            if ($mainImage) {
                $mainImage->deleteImage();
            }
            $filename = $request->get('main_image');
            if ($filename !== null) {
                $userFilename = TemporaryFile::moveToPermanence($filename, 'image');
                if ($userFilename) {
                    $buildingImage = new BuildingImage();
                    $buildingImage->filename = $filename;
                    $buildingImage->user_filename = $userFilename;
                    $buildingImage->type = BuildingImage::TYPE_MAIN;
                    $buildingImage->moderation_status = (int)$approved;
                    $this->images()->save($buildingImage);
                } else {
                    $savingFilesErrors[] = $filename;
                }
            }
        }
        if ($request->has('before_image')) {
            $mainImage = $this->beforeImage($approved)->getResults();
            if ($mainImage) {
                $mainImage->deleteImage();
            }
            $filename = $request->get('before_image');
            if ($filename !== null) {
                $userFilename = TemporaryFile::moveToPermanence($filename, 'image');
                if ($userFilename) {
                    $buildingImage = new BuildingImage();
                    $buildingImage->filename = $filename;
                    $buildingImage->user_filename = $userFilename;
                    $buildingImage->type = BuildingImage::TYPE_BEFORE;
                    $buildingImage->moderation_status = (int)$approved;
                    $this->images()->save($buildingImage);
                } else {
                    $savingFilesErrors[] = $filename;
                }
            }
        }
        if ($request->has('after_image')) {
            $mainImage = $this->afterImage($approved)->getResults();
            if ($mainImage) {
                $mainImage->deleteImage();
            }
            $filename = $request->get('after_image');
            if ($filename !== null) {
                $userFilename = TemporaryFile::moveToPermanence($filename, 'image');
                if ($userFilename) {
                    $buildingImage = new BuildingImage();
                    $buildingImage->filename = $filename;
                    $buildingImage->user_filename = $userFilename;
                    $buildingImage->type = BuildingImage::TYPE_AFTER;
                    $buildingImage->moderation_status = (int)$approved;
                    $this->images()->save($buildingImage);
                } else {
                    $savingFilesErrors[] = $filename;
                }
            }
        }

        //Update video
        if ($request->has('video')) {
            $video = $this->video($approved)->getResults();
            if ($video) {
                $video->deleteVideo();
            }
            $filename = $request->get('video');
            if ($filename !== null) {
                $userFilename = TemporaryFile::moveToPermanence($filename, 'video');
                if ($userFilename) {
                    $buildingVideo = new BuildingVideo();
                    $buildingVideo->filename = $filename;
                    $buildingVideo->user_filename = $userFilename;
                    $buildingVideo->moderation_status = (int)$approved;
                    $this->video()->save($buildingVideo);
                } else {
                    $savingFilesErrors[] = $filename;
                }
            }
        }

        //Update video
        if ($request->has('audio')) {
            $audio = $this->audio($approved)->getResults();
            if ($audio) {
                $audio->deleteAudio();
            }
            $filename = $request->get('audio');
            if ($filename !== null) {
                $userFilename = TemporaryFile::moveToPermanence($filename, 'audio');
                if ($userFilename) {
                    $buildingAudio = new BuildingAudio();
                    $buildingAudio->filename = $filename;
                    $buildingAudio->user_filename = $userFilename;
                    $buildingAudio->moderation_status = (int)$approved;
                    $this->audio()->save($buildingAudio);
                } else {
                    $savingFilesErrors[] = $filename;
                }
            }
        }

        return $savingFilesErrors;
    }

    /**
     * Get buildings by moderation status.
     *
     * @param array $status
     * @return array|null
     */
    public static function getBuildingsByApiStatus($status)
    {
        if (!in_array($status, self::API_STATUSES)) {
            return null;
        }

        switch ($status) {
            case self::API_STATUSES[0]:
                return Building::pending()->get();
            case self::API_STATUSES[1]:
                return Building::approved()->get();
            case self::API_STATUSES[2]:
                return Building::rejected()->get();
            case self::API_STATUSES[3]:
                return Building::postponed()->get();
        }
    }

    /**
     * Get building creator
     *
     * @return integer|null
     */
    public function getCreatorId()
    {
        $audit = $this->audits()
            ->where('event', 'created')
            ->first();

        return $audit ? $audit->user_id : null;
    }

    /**
     * Get last changes by user.
     *
     * @return array|null
     */
    public function getNewValues()
    {
        $creatorId = $this->getCreatorId();
        $admin = Admin::find($creatorId);

        if ($admin && $admin->role !== AdminRole::USER) {
            return [];
        }

        $newValues = [];

        if ($creatorId !== null) {
            $lastUserUpdatedAudit = $this->audits()
                ->where('user_id', $creatorId)
                ->where('event', 'updated')
                ->where('new_values', '<>', '[]')
                ->latest()
                ->first();

            if ($lastUserUpdatedAudit !== null) {
                foreach ($lastUserUpdatedAudit->new_values as $key => $value) {
                    $value = is_bool($value) ? (int)$value : $value;
                    if ($this->attributes[$key] !== $value) {
                        $newValues[$key] = $value;
                    }
                }
            }

            $lastFile = $this->mainImage(false)->getResults();
            $currentFile = $this->mainImage()->getResults();
            if ($lastFile !== null && $lastFile !== $currentFile) {
                $newValues['main_image'] = new BuildingImageResource($lastFile);
            }

            $lastFile = $this->beforeImage(false)->getResults();
            $currentFile = $this->beforeImage()->getResults();
            if ($lastFile !== null && $lastFile !== $currentFile) {
                $newValues['before_image'] = new BuildingImageResource($lastFile);
            }

            $lastFile = $this->afterImage(false)->getResults();
            $currentFile = $this->afterImage()->getResults();
            if ($lastFile !== null && $lastFile !== $currentFile) {
                $newValues['after_image'] = new BuildingImageResource($lastFile);
            }

            $lastFiles = $this->anotherImages(false)->getResults();
            $currentFiles = $this->anotherImages()->getResults();
            if ($lastFiles !== null && count($lastFiles) > 0 && count($lastFiles->diffAssoc($currentFiles)) > 0) {
                $newValues['another_images'] = BuildingImageResource::collection($lastFiles);
            }

            $lastFile = $this->video(false)->getResults();
            $currentFile = $this->video()->getResults();
            if ($lastFile !== null && $lastFile !== $currentFile) {
                $newValues['video'] = new BuildingVideoResource($lastFile);
            }

            $lastFile = $this->audio(false)->getResults();
            $currentFile = $this->audio()->getResults();
            if ($lastFile !== null && $lastFile !== $currentFile) {
                $newValues['audio'] = new BuildingAudioResource($lastFile);
            }
        }

        return $newValues;
    }

    /**
     * Approve new files
     *
     * @return bool
     */
    public function approveFiles()
    {
        // delete old files
        $this->deleteFiles();

        // approve new files
        $buildingImages = $this->images(false)->getResults();
        foreach ($buildingImages as $buildingImage) {
            $buildingImage->update(['moderation_status' => Building::APPROVED_FILE]);
        }

        $buildingVideo = $this->video(false)->getResults();
        if ($buildingVideo !== null) {
            $buildingVideo->update(['moderation_status' => Building::APPROVED_FILE]);
        }

        $buildingAudio = $this->audio(false)->getResults();
        if ($buildingAudio !== null) {
            $buildingAudio->update(['moderation_status' => Building::APPROVED_FILE]);
        }

        return true;
    }

    /**
     * Delete all files
     *
     * @param bool $approved
     * @return bool
     */
    public function deleteFiles($approved = true)
    {
        $buildingImages = $this->images($approved)->getResults();
        foreach ($buildingImages as $buildingImage) {
            $buildingImage->deleteImage();
        }
        $buildingVideo = $this->video($approved)->getResults();
        if ($buildingVideo !== null) {
            $buildingVideo->deleteVideo();
        }
        $buildingAudio = $this->audio($approved)->getResults();
        if ($buildingAudio !== null) {
            $buildingAudio->deleteAudio();
        }
        return true;
    }
}
