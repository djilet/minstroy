<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PageCreateRequest;
use App\Http\Requests\Admin\PageUpdateRequest;
use App\Models\Menu;
use App\Models\Page;
use App\Rules\SlugValidator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(Menu::with('pages.children')->get());
        
        return view('admin.dashboard', [
            'menus' => Menu::with('pages.children')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.page.create', [
            'menus' => Menu::with('pages')->get(),
            'templates' => config('meteor.templates'),
            'openMenuId' => \request('openMenuId'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Admin\PageCreateRequest|\Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PageCreateRequest $request)
    {
        Auth::guard('admin')->user()->can('create', Page::class);
        
        $page = new Page($request->all());
        $page->save();
              
        return redirect()->to(route('admin.page.index') . '#menu-id-'.$page->menu_id);
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
        $page = Page::findOrFail($id);
        
        return view('admin.page.edit', [
            'page' => $page,
            'menus' => Menu::with('pages')->get(),
            'templates' => config('meteor.templates')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Admin\PageUpdateRequest|\Illuminate\Http\Request $request
     * @param  int                                                                $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(PageUpdateRequest $request, $id)
    {
        $page = Page::findOrFail($id);
        Auth::guard('admin')->user()->can('update', $page);
        
        $page->fill($request->all());
        $page->save();
        
        return redirect()->route('admin.page.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        Auth::guard('admin')->user()->can('delete', $page);
        $page->delete();
        
        return redirect()->route('admin.page.index');
    }


    public function setActive($id)
    {
        $page = Page::findOrFail($id);
        $active = (\request('active') == 1) ? 1 : 0;
        $page->active = $active;
        $page->save();
        
        return response()->json([
            'success' => true
        ]);
    }

    public function sort(Request $request)
    { 
        /** @var \App\Models\Page $page */
        $page = Page::findOrFail($request->input('id'));
        Auth::guard('admin')->user()->can('update', $page);
        
        if ($request->exists('parent')) {
            $parent = $request->input('parent');
            $parent = $parent > 0 ? $parent : null;
            $page->parent_id = $parent;
            $page->save();
            
            if ($request->exists('before')) {
                $before = $request->input('before');
                $beforePage = Page::find(intval($before));
                if ($beforePage) {
                    $page->beforeNode($beforePage)->save();
                }
            } else if ($request->exists('after')) {
                $after = $request->input('after');
                $afterPage = Page::find(intval($after));
                if ($afterPage) {
                    $page->afterNode($afterPage)->save();
                }
            }
            
        } else {
            
            $move = $request->input('move');
            if ($move > 0) {
                $page->down(abs($move));
            } else {
                $page->up(abs($move));
            }
            
        }
        
        return response()->json([
            'success' => true
        ]);
    }
}
