<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('buildings')->insert([
            [
                'title' => 'Первый объект',
                'address' => 'Москва, ул. Зорге, д. 1',
                'building_type_id' => '1',
                'region_id' => '1',
                'latitude' => '55.778699',
                'longitude' => '37.512362',
                'participant' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Второй объект',
                'address' => 'Москва, ул. Донецкая, д. 1',
                'building_type_id' => '2',
                'region_id' => '1',
                'latitude' => '55.651115',
                'longitude' => '37.719702',
                'participant' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Третий объект',
                'address' => 'Новосибирск, ул. Ленина, д. 1',
                'building_type_id' => '1',
                'region_id' => '2',
                'latitude' => '55.029657',
                'longitude' => '82.91866',
                'participant' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Четвертый объект',
                'address' => 'Новосибирск, ул. Титова, д. 1',
                'building_type_id' => '2',
                'region_id' => '2',
                'latitude' => '54.981836',
                'longitude' => '82.889815',
                'participant' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
