<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Rules\SlugValidator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.menu.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Auth::guard('admin')->user()->can('create', Menu::class);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'slug' => [
                'required', 
                new SlugValidator('menus', [
                    'lang' => editor_lang()
                ])
            ],
        ]);

        if ($validator->passes()) {
            $menu = new Menu($request->all());
            if ($menu->save()) {
                return response()->json([
                    'success' => true,
                    'type' => 'create',
                    'id' => $menu->id,
                    'title' => $menu->title,
                ], 200);
            }
        }

        return response()->json([
            'success' => false,
            'message' => $validator->errors()
        ], 422);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('admin.menu.edit', [
            'menu' => $menu
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'slug' => [
                'required',
                new SlugValidator('menus', [
                    ['id', '<>', $id],
                    'lang' => editor_lang(),
                ])
            ],
        ]);
        
        if ($validator->passes()) {
            
            $menu = Menu::findOrFail($id);
            Auth::guard('admin')->user()->can('update', $menu);
            
            $menu->title = $request->get('title');
            $menu->slug = $request->get('slug');

            if ($menu->save()) {
                return response()->json([
                    'success' => true,
                    'type' => 'update',
                    'id' => $menu->id,
                    'title' => $menu->title,
                ], 200);
            }
        }
        
        return response()->json([
            'success' => false,
            'message' => $validator->errors()
        ], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        Auth::guard('admin')->user()->can('delete', $menu);

        if ($menu->delete()) {
            return response()->json([
                'success' => true,
            ], 200);
        }

        return response()->json([
            'success' => false,
        ], 422);
    }
}
