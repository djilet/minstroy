<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Login in admin panel
     *
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        if (!Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            return $this->sendError('Invalid email or password', [], 401);
        }

        $admin = Admin::where('email', $request->email)->first();

        if ($admin->active === false) {
            return $this->sendError('User is inactive', [], 401);
        }

        $authToken = $admin->createToken('auth-token')->plainTextToken;

        return $this->sendResponse([
            'user_id' => $admin->id,
            'role' => $admin->role,
            'access_token' => $authToken,
            'token_type' => 'Bearer'
        ], 'Successful authentication.');
    }
}