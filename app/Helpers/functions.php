<?php

if (! function_exists('pages')) {
    function pages($menu_slug) {
        static $menus = [];
        
        if (! isset($menus[$menu_slug])) {
            $pages = null;
            $menu = App\Models\Menu::where('slug', $menu_slug)->first();
            if ($menu) {
                $pages = $menu->pages()
                    ->select('pages.*', 'links.link_url', 'links.target', 'links.type')
                    ->leftJoin('links', 'pages.link_id', '=', 'links.id')
                    ->with('ancestors')
                    ->get();
            }
            
            if ($pages) {
                $menus[$menu_slug] = $pages->toTree();
            } else {
                $menus[$menu_slug] = [];
            }
        }
        
        return $menus[$menu_slug];
    }
}

if (! function_exists('locales')) {
    function locales() {
        static $locales = null;
        
        if ($locales == null) {
            $list = [];
            $currentLocale = app()->getLocale();
            foreach(config('meteor.locales') as $key => $title) {
                $locale = new StdClass;
                $locale->slug = $key;
                $locale->title = $title;
                $locale->selected = $currentLocale == $key;
                $list[] = $locale;
            }
            
            $locales = collect($list);
        }
        
        return $locales;
    }
}

if (! function_exists('editor_locales')) {
    function editor_locales() {
        static $editorLocales = null;
        
        if ($editorLocales == null) {
            $list = [];
            $currentLocale = session('editorLang', 'ru');
            foreach(config('meteor.locales') as $key => $title) {
                $locale = new StdClass;
                $locale->slug = $key;
                $locale->title = $title;
                $locale->selected = $currentLocale == $key;
                $list[] = $locale;
            }
            
            $editorLocales = collect($list);
        }
        
        return $editorLocales;
    }
}

if (! function_exists('editor_lang')) {
    function editor_lang() {
        return session('editorLang', config('app.locale'));
    }
}