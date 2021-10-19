<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CatalogItemPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_items_price', function (Blueprint $table) {
            $table->integer('item_id')->unsigned();
            $table->integer('currency_id')->unsigned();
            $table->double('value', 15, 4)->unsigned();

            $table->primary(['item_id', 'currency_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_items_price');
    }
}
