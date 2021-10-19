<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\Controller;
use App\Models\TemporaryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * Upload file to temp directory.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'image' => 'required_without_all:video,audio|image',
            'video' => 'required_without_all:image,audio|mimes:mp4,webm,m4v,mov,qt',
            'audio' => 'required_without_all:image,video|mimes:mp3,wav,wave',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        $file = $request->file('image') ?? $request->file('video') ?? $request->file('audio');

        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $file->storeAs('tmp', $filename);

        $tmpFileDb = new TemporaryFile();
        $tmpFileDb->id = $filename;
        $tmpFileDb->user_filename = $file->getClientOriginalName();
        $tmpFileDb->created_at = Carbon::now();
        $tmpFileDb->save();

        return $this->sendResponse($filename, 'File uploaded successfully.');
    }
}
