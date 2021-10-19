<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateBuildingTableAddModerationColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->smallInteger('moderation_status')->default(0);
            $table->dateTime('moderated_at')->nullable();
            $table->unsignedInteger('moderated_by')->nullable();
            $table->foreign('moderated_by')->references('id')->on('admins');
        });
        DB::table('buildings')->update(['moderation_status' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn('moderation_status');
            $table->dropColumn('moderated_at');
            $table->dropForeign(['moderated_by']);
            $table->dropColumn('moderated_by');
        });
    }
}
