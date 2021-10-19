<?php

namespace App\Traits;


use App\Scopes\LanguageScope;

trait LocaleTrait
{
    protected $localeColumn = 'lang';

    public static function bootLocaleTrait()
    {
        static::addGlobalScope(new LanguageScope());
    }

    public function getLocaleColumn()
    {
        return $this->localeColumn;
    }
}