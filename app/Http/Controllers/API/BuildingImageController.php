<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class BuildingImageController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param string $filename
     * @return Response
     */
    public function show($filename)
    {
        $path = 'files/building/image/' . $filename;
        $storage = Storage::disk('public');

        if (!$storage->exists($path)) {
            return $this->sendError('Image not found.');
        }

        $file = $storage->get($path);
        $mime = $storage->mimeType($path);

        return $this->sendFile($file, $mime);
    }
}
