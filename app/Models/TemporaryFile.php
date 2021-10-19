<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TemporaryFile extends Model
{
    public $incrementing = false;
    public $timestamps = false;


    /**
     * Move file to the permanent storage.
     *
     * @param string $filename
     * @param string $directory
     * @return bool|string
     */
    public static function moveToPermanence($filename, $directory)
    {
        $tmpFile = TemporaryFile::find($filename);
        $path = 'tmp/' . $filename;

        if ($tmpFile == null || !Storage::disk('local')->exists($path)) {
            return false;
        }

        Storage::disk('local')->move($path, 'public/files/building/' . $directory . '/' . $filename);

        $userFilename = $tmpFile->user_filename;
        $tmpFile->delete();

        return $userFilename;
    }
}
