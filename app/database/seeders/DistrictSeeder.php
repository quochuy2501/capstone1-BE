<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('districts')->delete();

        DB::table('districts')->truncate();

        DB::table('districts')->insert([
            ['name_district' => 'Quận Cẩm Lệ'],
            ['name_district' => 'Quận Hải Châu'],
            ['name_district' => 'Quận Liên Chiểu'],
            ['name_district' => 'Quận Ngũ Hành Sơn'],
            ['name_district' => 'Quận Sơn Trà'],
            ['name_district' => 'Quận Thanh Khê'],
            ['name_district' => 'Huyện Hòa Vang'],
            ['name_district' => 'Huyện Hoàng Sa'],
        ]);
    }
}
