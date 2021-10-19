<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BuildingImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('building_images')->insert([
            [
                'filename' => Str::uuid() . '.jpeg',
                'user_filename' => '1.jpeg',
                'building_id' => '1',
                'type' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'filename' => Str::uuid() . '.jpeg',
                'user_filename' => '2.jpeg',
                'building_id' => '1',
                'type' => '2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'filename' => Str::uuid() . '.jpeg',
                'user_filename' => '3.jpeg',
                'building_id' => '1',
                'type' => '3',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'filename' => Str::uuid() . '.jpeg',
                'user_filename' => '4.jpeg',
                'building_id' => '1',
                'type' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'filename' => Str::uuid() . '.jpeg',
                'user_filename' => '5.jpeg',
                'building_id' => '1',
                'type' => '4',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
