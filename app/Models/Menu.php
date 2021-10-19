<?php

namespace App\Models;

use App\Events\Admin\MenuDeleted;
use App\Scopes\LanguageScope;
use App\Traits\LocaleTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Menu extends Model
{
    use LocaleTrait;
    
    protected $fillable = ['title', 'slug'];
    
    protected $dispatchesEvents = [
        'deleted' => MenuDeleted::class
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        self::creating(function() {
            $this->lang = editor_lang();
        });
    }
    
    public function pages()
    {
        return $this->hasMany('App\Models\Page')->defaultOrder();
    }
    
}
