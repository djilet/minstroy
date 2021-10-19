<?php

use App\Helpers\SeoHelper;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id')->unsigned()->index();
            $table->string('lang')->length(4)->index();
            $table->string('title');
            $table->string('slug')->nullable()->index();
            $table->string('link_id')->nullable();
            $table->text('content')->nullable();
            $table->boolean('active')->default(false);
            $table->string('template')->nullable();
            SeoHelper::columns($table);
            $table->timestamps();
            NestedSet::columns($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
