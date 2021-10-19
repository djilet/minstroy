<?php
/**
 * Date:    14.02.18
 *
 * @author: dolphin54rus <dolphin54rus@gmail.com>
 */

namespace App\Helpers;


use Illuminate\Database\Schema\Blueprint;

class SeoHelper
{
    public static function columns(Blueprint $table)
    {
        $table->string('title_h1')->nullable();
        $table->string('meta_title')->nullable();
        $table->string('meta_keywords')->nullable();
        $table->string('meta_description')->nullable();
    }
}