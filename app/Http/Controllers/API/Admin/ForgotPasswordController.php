<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\Controller;
use App\Mail\AdminForgotPasswordMail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Forgot password for admins
     *
     * @param Request $request
     * @return Response
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        $admin = Admin::where('email', $request->only('email'))->first();

        if (is_null($admin)) {
            return $this->sendError('User with such mail was not found.');
        }

        $password = Str::random(13);
        $admin->password = Hash::make($password);
        $admin->save();

        $admin->tokens()->delete();

        Mail::to($request->only('email'))->send(new AdminForgotPasswordMail($password));

        return $this->sendResponse([], 'New password sent on your email.');
    }

}
