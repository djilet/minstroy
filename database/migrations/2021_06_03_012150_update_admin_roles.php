<?php

use App\Enum\AdminRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateAdminRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('role', 50)->default(AdminRole::ADMIN)->change();
        });
        DB::table('admins')->update(['role' => AdminRole::ADMIN]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('role', 50)->default(AdminRole::Administrator)->change();
        });
        DB::table('admins')->update(['role' => AdminRole::Integrator]);
    }
}
