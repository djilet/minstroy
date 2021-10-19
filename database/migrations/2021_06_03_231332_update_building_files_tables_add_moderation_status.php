<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateBuildingFilesTablesAddModerationStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('building_images', function (Blueprint $table) {
            $table->smallInteger('moderation_status')->default(1);
        });
        Schema::table('building_videos', function (Blueprint $table) {
            $table->smallInteger('moderation_status')->default(1);
        });
        Schema::table('building_audio', function (Blueprint $table) {
            $table->smallInteger('moderation_status')->default(1);
        });
        DB::table('building_images')->update(['moderation_status' => 1]);
        DB::table('building_videos')->update(['moderation_status' => 1]);
        DB::table('building_audio')->update(['moderation_status' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('building_images', function (Blueprint $table) {
            $table->dropColumn('moderation_status');
        });
        Schema::table('building_video', function (Blueprint $table) {
            $table->dropColumn('moderation_status');
        });
        Schema::table('building_audio', function (Blueprint $table) {
            $table->dropColumn('moderation_status');
        });
    }
}
