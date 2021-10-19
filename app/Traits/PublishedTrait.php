<?php

namespace App\Traits;


use App\Scopes\PublishedScope;

trait PublishedTrait
{
    protected $activeColumn = 'active';

    public static function bootPublishedTrait()
    {
        static::addGlobalScope(new PublishedScope());
    }

    public function getActiveColumn()
    {
        return $this->activeColumn;
    }
}