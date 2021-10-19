<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function indexPage()
    {
        $page = Page::findStaticPageOrFail(null, 'index');
        return view('index', ['page' => $page]);
    }
    
    public function rootPage(Request $request)
    {
        $slug = $request->route('slug');

        $page = Page::findStaticPageOrFail(null, $slug);
        return view('page', ['page' => $page]);
    }

    public function innerPage(Request $request)
    {
        $path = $request->route('path');
        $slug = $request->route('slug');
        
        $page = Page::findStaticPageOrFail($path, $slug);
        return view('page', ['page' => $page]);
    }
}
