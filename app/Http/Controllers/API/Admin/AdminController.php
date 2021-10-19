<?php

namespace App\Http\Controllers\API\Admin;

use App\Enum\AdminRole;
use App\Http\Controllers\API\Controller;
use App\Http\Resources\Admin\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Admin::class)) {
            return $this->sendError('You cannot view another admins', [], 403);
        }

        $admins = Admin::all();

        return $this->sendResponse(AdminResource::collection($admins), 'Admins retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('create', Admin::class)) {
            return $this->sendError('You cannot create admins', [], 403);
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'email' => 'required|email|string|unique:admins',
            'password' => 'required|string|max:255|min:8',
            'position' => 'required|string|max:255',
            'active' => 'boolean',
            'role' => 'string|in:' . implode(',', [AdminRole::ADMIN, AdminRole::USER]),
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        $admin = Admin::Create($input);
        $newAdmin = Admin::find($admin->id);

        return $this->sendResponse(new AdminResource($newAdmin), 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $admin = Admin::find($id);

        if (is_null($admin)) {
            return $this->sendError('User not found.');
        }

        if ($request->user()->cannot('view', $admin)) {
            return $this->sendError('You cannot view this admin', [], 403);
        }

        return $this->sendResponse(new AdminResource($admin), 'User retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::find($id);

        if (is_null($admin)) {
            return $this->sendError('User not found.');
        }

        if ($request->user()->cannot('update', $admin)) {
            return $this->sendError('You cannot update this admin', [], 403);
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'middle_name' => 'string|max:255',
            'email' => 'email|string|unique:admins',
            'password' => 'string|max:255|min:8',
            'position' => 'string|max:255',
            'active' => 'boolean',
            'role' => 'string|in:' . implode(',', [AdminRole::ADMIN, AdminRole::USER]),
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        if ($request->has('active') && $request->user()->cannot('changeActivity', $admin)) {
            return $this->sendError('User can not change activity.', [], 403);
        }

        if ($request->has('role') && $request->user()->cannot('changeRole', $admin)) {
            return $this->sendError('User can not change role.', [], 403);
        }

        $isEmailChanged = isset($input['email']) && $input['email'] !== $admin->email;
        $isRoleChanged = isset($input['role']) && $input['role'] !== $admin->role;
        $isInactive = isset($input['active']) && $input['active'] === false;

        if ($isEmailChanged || $isRoleChanged || $isInactive || isset($input['password'])) {
            $admin->tokens()->delete();
        }

        if (isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        $admin->update($input);

        return $this->sendResponse(new AdminResource($admin), 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $admin = Admin::find($id);

        if (is_null($admin)) {
            return $this->sendError('User not found.');
        }

        if ($request->user()->cannot('delete', $admin)) {
            return $this->sendError('You cannot delete this admin', [], 403);
        }

        $admin->delete();

        return $this->sendResponse(new AdminResource($admin), 'User deleted successfully.');
    }
}
