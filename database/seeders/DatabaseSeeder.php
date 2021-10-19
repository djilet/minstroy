<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        
        DB::table('admins')->insert([
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'middle_name' => 'Иванович',
            'position' => 'начальник',
            'email' => 'info@fokgroup.com',
            'role' => 'admin',
            'timezone' => 'Asia/Novosibirsk',
            'password' => Hash::make('admin'),
            'remember_token' => 'VvvaNIIfLceUMLYWdhHh1UDgHRftnaWJfwSY9lMn76JfO5vFw9xyESy1qByM',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('menus')->insert([
            'title' => 'Меню',
            'lang' => 'ru',
            'slug' => 'main',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('pages')->insert([
            'title' => 'Главная',
            'menu_id' => 1,
            'lang' => 'ru',
            'slug' => 'index',
            'active' => 1,
            '_lft' => 1,
            '_rgt' => 2,
            'content' => 'index page content',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $this->call([
            RegionSeeder::class,
            BuildingTypeSeeder::class,
            BuildingSeeder::class,
            BuildingImageSeeder::class,
            BuildingVideoSeeder::class,
            BuildingAudioSeeder::class,
        ]);
    }
}
