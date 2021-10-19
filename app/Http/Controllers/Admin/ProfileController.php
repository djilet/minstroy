<?php

namespace App\Http\Controllers\Admin;

use App\Enum\AdminRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileCreateRequest;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Models\Admin;
use App\Traits\PaginationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use PaginationHelper;

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $admins = Admin::paginate();
        $this->existPageNumber($request, $admins);
        
        return view('admin.profile.index', [
            'admins' => $admins
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Admin::class);
        
        return view('admin.profile.create', [
            'adminRole' => new AdminRole(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Admin\ProfileCreateRequest|\Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileCreateRequest $request)
    {
        $admin = new Admin($request->all());
        $admin->save();
        
        return redirect()->route('admin.profile.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $admin = Admin::findOrFail($id);
        $this->authorize('view', $admin);
        
        return view('admin.profile.show', [
            'admin' => $admin
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        $this->authorize('update', $admin);
        
        return view('admin.profile.edit', [
            'admin' => $admin,
            'adminRole' => new AdminRole(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Admin\ProfileUpdateRequest|\Illuminate\Http\Request $request
     * @param  int                                                                   $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileUpdateRequest $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $request->ifChangePassword($admin);
        $admin->update($request->updateAttributes());
        
        return redirect()->route('admin.profile.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $this->authorize('delete', $admin);
        $admin->delete();
        
        return redirect()->back();
    }

    public function destroys(Request $request)
    {
        $userIDs = $request->input('UserIDs');
        if (!empty($userIDs)) {
            $admin = Auth::guard('admin')->user();
            $profiles = Admin::whereIn('id', $userIDs)->get();

            foreach ($profiles as $profile) {
                if ($admin->can('delete', $profile)) {
                    $profile->delete();
                }
            }
        }
        
        return redirect()->back();
    }
}
