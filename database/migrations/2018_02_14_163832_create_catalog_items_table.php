<?php

use App\Helpers\SeoHelper;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateCatalogItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_items', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');
            $table->string('slug')->index();
            $table->string('sku')->nullable();
            $table->string('description')->nullable();
            $table->text('content');
            $table->integer('sort_order');
            $table->boolean('active')->default(true);

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
        Schema::dropIfExists('catalog_items');
    }
}
