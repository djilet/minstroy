<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Rules\SlugValidator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'menus' => Menu::with('pages')->get()
        ]);
    }


    public function editorLang(Request $request)
    {
        $lang = $request->input('editorLang');
        if (array_key_exists($lang, config('meteor.locales'))) {
            session(['editorLang' => $lang]);
        }
        
        return redirect()->back();
    }

    public function createSlug(Request $request)
    {
        $slug = str_slug($request->input('title'));
        
        $condition = [];
        if ($request->exists('id')) {
            $condition['id'] = $request->input('id');
        }
        if ($request->exists('parent_id')) {
            $condition['parent_id'] = $request->input('parent_id');
        }
        if (($lang = $request->input('lang')) !== null) {
            $condition['lang'] = $lang;
        }

        $validator = Validator::make(['slug' => &$slug], [
            'slug' => ['required', new SlugValidator($request->input('table'), $condition)]
        ]);
        
        if ($validator->fails()) {
            $i = 1;
            do {

                $validator = Validator::make(['slug' => $slug . '-' . ++$i], [
                    'slug' => ['required', new SlugValidator($request->input('table'), $condition)]
                ]);

            } while ($validator->fails() and $i < 500);
            
            if ($i < 500) {
                return response()->json([
                    'slug' => $slug . '-' . $i
                ]);
            }
        } else {
            return response()->json([
                'slug' => $slug
            ]);
        }
    }
}
