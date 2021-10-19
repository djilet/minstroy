<?php

namespace App\Helpers;


use App\Models\Page;
use Illuminate\Support\Facades\Route;

class ModuleHelper
{
    public static function routes()
    {
        $pages = Page::select('slug', 'template')->whereNull('link_id')->whereNotNull('template')->get();
        $modules = config('meteor.modules');

        $named_module = [];
        
        foreach ($pages as $page) {
            list($module, $template) = explode('.', $page->template);
            if (array_key_exists($module, $modules)) {
                
                $conf = $modules[$module];
                
                if (in_array($page->template, $named_module)) {
                    Route::get($page->full_slug, $conf['as'] . '@index');
                    Route::get($page->full_slug . '/{category}', $conf['as'] . '@category');
                    Route::get($page->full_slug . '/{category}/{product}.html', $conf['as'] . '@product');
                } else {
                    Route::get($page->full_slug, $conf['as'] . '@index')->name('catalog.' . $template . '.index');
                    Route::get($page->full_slug . '/{category}', $conf['as'] . '@category')->name('catalog.' . $template . '.category');
                    Route::get($page->full_slug . '/{category}/{product}.html', $conf['as'] . '@product')->name('catalog.' . $template . '.product');
                    
                    $named_module[] = $page->template;
                }
                
            }
        }
    }
}