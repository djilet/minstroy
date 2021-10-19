<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * return success response
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        if (isset($result->pagination)) {
            $response = [
                'success' => true,
                'data' => $result->collection->values(),
                'pagination' => $result->pagination,
                'message' => $message,
            ];
        } else {
            $response = [
                'success' => true,
                'data' => $result,
                'message' => $message,
            ];
        }


        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * return file
     *
     * @return \Illuminate\Http\Response
     */
    public function sendFile($file, $contentType)
    {
        return response($file, 200, ['Content-Type' => $contentType]);
    }
}