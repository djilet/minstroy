<?php

namespace App\Models;

use App\Traits\LocaleTrait;
use App\Traits\PublishedTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Kalnoy\Nestedset\NodeTrait;

class Page extends Model
{
    use NodeTrait, 
        PublishedTrait, 
        LocaleTrait;
    
    protected $fillable = [
        'menu_id', 'title', 'slug', 'content', 'active', 'template', 
        'title_h1', 'meta_title', 'meta_keywords', 'meta_description',
        'parent_id',
    ];

    private $url = null;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        self::creating(function() {
            $this->lang = editor_lang();
        });
    }

//    protected function getScopeAttributes()
//    {
//        return [ 'menu_id' ];
//    }

    public function menu()
    {
        return $this->belongsTo('App\Models\Menu');
    }
    public function link()
    {
        return $this->hasOne('App\Models\Link', 'id', 'link_id');
    }

    public static function findStaticPage($path, $slug)
    {
        if ($path == null) {
            
            return self::where('slug', $slug)
                ->whereNull('parent_id')
                ->first();

        } else {

            $pages = self::where('slug', $slug)
                ->with('ancestors')
                ->get();
            
            if (count($pages) > 0) {
                foreach ($pages as $page) {
                    $pagePath = $page->ancestors->pluck('slug')->implode('/');
                    if ($path == $pagePath) {
                        return $page;
                    }
                }
            }
        }
        
        return null;
    }

    public static function findStaticPageOrFail($path, $slug)
    {
        $page = self::findStaticPage($path, $slug);
        
        if ($page == null) {
            abort(404, 'Page Not Found');
        }
        
        return $page;
    }

    public function getUrlAttribute()
    {
        if ($this->url == null) {
            
            $lang = App::getLocale();
            $lang = config('app.fallback_locale') == $lang ? null : $lang;
            
            if ($this->isLink()) {
                if (isset($this->link_url)) {
                    
                    $this->url = $this->type == 'internal' 
                        ? route('page.static.root', [
                            'slug' => $this->link_url,
                            'lang' => $lang,
                        ]) 
                        : $this->link_url;
                    
                } else if ($this->link) {

                    $this->url = $this->type == 'internal'
                        ? route('page.static.root', [
                            'slug' => $this->link->link_url,
                            'lang' => $lang,
                        ])
                        : $this->link->link_url;
                    
                } else {
                    $this->url = '';
                }
                
                return $this->url;
            }

            if ($this->parent_id == null and $this->slug == 'index') {
                $this->url = route('index', [
                    'lang' => $lang
                ]);
                return $this->url;
            }

            if ($this->parent_id > 0) {
                $this->url = route('page.static', [
                    'path' => $this->ancestors->pluck('slug')->implode('/'),
                    'slug' => $this->slug,
                    'lang' => $lang,
                ]);
                return $this->url;
            }

            $this->url = route('page.static.root', [
                'slug' => $this->slug,
                'lang' => $lang,
            ]);
        }
        
        return $this->url;
    }

    public function getFullSlugAttribute()
    {
        return $this->pathSlug();
    }

    public function pathSlug($excludeSelf = false)
    {
        $lang = App::getLocale();
        $lang = config('app.fallback_locale') == $lang ? '' : $lang.'/';
        $path = $this->ancestors->pluck('slug')->implode('/');
        
        return $lang.(!empty($path) ? $path.'/' : '').( $excludeSelf ? '' : $this->slug);
    }

    public function isLink()
    {
        return $this->link_id > 0;
    }

    public function targetAttr()
    {
        if (empty($this->target)) {
            return '';
        }
        
        return ' target="'.$this->target.'"';
    }
    
}
