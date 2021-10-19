<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class BuildingVideoController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param string $filename
     * @return Response
     */
    public function show($filename)
    {
        $path = 'files/building/video/' . $filename;
        $storage = Storage::disk('public');

        if (!$storage->exists($path)) {
            return $this->sendError('Video not found.');
        }

        $file = $storage->get($path);
        $mime = $storage->mimeType($path);
        $rangeHeader = request()->header('Range');
        $headers = ['Content-Type' => $mime];
        if ($rangeHeader) {
            return $this->getResponseStream($path, $file, $rangeHeader, $headers);
        }

        return $this->sendFile($file, $mime);
    }

    /**
     *
     * @param string $fullFilePath
     * @param string $fileContents
     * @param string $rangeRequestHeader
     * @param array  $responseHeaders
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public static function getResponseStream($fullFilePath, $fileContents, $rangeRequestHeader, $responseHeaders) {
        $stream = Storage::disk('public')->readStream($fullFilePath);
        $fileSize = strlen($fileContents);
        $fileSizeMinusOneByte = $fileSize - 1; //because it is 0-indexed. https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.16
        list($param, $rangeHeader) = explode('=', $rangeRequestHeader);
        if (strtolower(trim($param)) !== 'bytes') {
            abort(400, "Invalid byte range request"); //Note, this is not how https://stackoverflow.com/a/29997555/470749 did it
        }
        list($from, $to) = explode('-', $rangeHeader);
        if ($from === '') {
            $end = $fileSizeMinusOneByte;
            $start = $end - intval($from);
        } elseif ($to === '') {
            $start = intval($from);
            $end = $fileSizeMinusOneByte;
        } else {
            $start = intval($from);
            $end = intval($to);
        }
        $length = $end - $start + 1;
        $httpStatusCode = 206;
        $responseHeaders['Content-Range'] = sprintf('bytes %d-%d/%d', $start, $end, $fileSize);
        $responseStream = response()->stream(function() use ($stream, $start, $length) {
            fseek($stream, $start, SEEK_SET);
            echo fread($stream, $length);
            fclose($stream);
        }, $httpStatusCode, $responseHeaders);
        return $responseStream;
    }
}
