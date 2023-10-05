<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wards')->delete();

        DB::table('wards')->truncate();

        DB::table('wards')->insert([
            ['name_ward' => 'Hòa Thọ Đông', "id_district" => "1"],
            ['name_ward' => 'Hòa An', "id_district" => "1"],
            ['name_ward' => 'Hòa Thọ Tây', "id_district" => "1"],
            ['name_ward' => 'Hòa Phát', "id_district" => "1"],
            ['name_ward' => 'Hòa Xuân', "id_district" => "1"],
            ['name_ward' => 'Khuê Trung', "id_district" => "1"],
            ['name_ward' => 'Bình Hiên', "id_district" => "2"],
            ['name_ward' => 'Bình Thuận', "id_district" => "2"],
            ['name_ward' => 'Hải Châu I', "id_district" => "2"],
            ['name_ward' => 'Hải Châu II', "id_district" => "2"],
            ['name_ward' => 'Hòa Cường Bắc', "id_district" => "2"],
            ['name_ward' => 'Hòa Cường Nam', "id_district" => "2"],
            ['name_ward' => 'Hòa Thuận Đông', "id_district" => "2"],
            ['name_ward' => 'Hòa Thuận Tây', "id_district" => "2"],
            ['name_ward' => 'Nam Dương', "id_district" => "2"],
            ['name_ward' => 'Phước Ninh', "id_district" => "2"],
            ['name_ward' => 'Thạch Thang', "id_district" => "2"],
            ['name_ward' => 'Thanh Bình', "id_district" => "2"],
            ['name_ward' => 'Thuận Phước', "id_district" => "2"],
            ['name_ward' => 'Hòa Hiệp Bắc', "id_district" => "3"],
            ['name_ward' => 'Hòa Hiệp Nam', "id_district" => "3"],
            ['name_ward' => 'Hòa Khánh Bắc', "id_district" => "3"],
            ['name_ward' => 'Hòa Khánh Nam', "id_district" => "3"],
            ['name_ward' => 'Hòa Minh', "id_district" => "3"],
            ['name_ward' => 'Hòa Hải', "id_district" => "4"],
            ['name_ward' => 'Hòa Quý', "id_district" => "4"],
            ['name_ward' => 'Khuê Mỹ', "id_district" => "4"],
            ['name_ward' => 'Mỹ An', "id_district" => "4"],
            ['name_ward' => 'An Hải Bắc', "id_district" => "5"],
            ['name_ward' => 'An Hải Đông', "id_district" => "5"],
            ['name_ward' => 'An Hải Tây', "id_district" => "5"],
            ['name_ward' => 'Mân Thái', "id_district" => "5"],
            ['name_ward' => 'Nại Hiên Đông', "id_district" => "5"],
            ['name_ward' => 'Phước Mỹ', "id_district" => "5"],
            ['name_ward' => 'Thọ Quang', "id_district" => "5"],
            ['name_ward' => 'An Khê', "id_district" => "6"],
            ['name_ward' => 'Chính Gián', "id_district" => "6"],
            ['name_ward' => 'Hòa Khê', "id_district" => "6"],
            ['name_ward' => 'Tam Thuận', "id_district" => "6"],
            ['name_ward' => 'Tân Chính', "id_district" => "6"],
            ['name_ward' => 'Thạc Gián', "id_district" => "6"],
            ['name_ward' => 'Thanh Khê Đông', "id_district" => "6"],
            ['name_ward' => 'Thanh Khê Tây', "id_district" => "6"],
            ['name_ward' => 'Vĩnh Trung', "id_district" => "6"],
            ['name_ward' => 'Xuân Hà', "id_district" => "6"],
            ['name_ward' => 'Hòa Phong', "id_district" => "7"],
            ['name_ward' => 'Hòa Bắc', "id_district" => "7"],
            ['name_ward' => 'Hòa Châu', "id_district" => "7"],
            ['name_ward' => 'Hòa Khương', "id_district" => "7"],
            ['name_ward' => 'Hòa Liên', "id_district" => "7"],
            ['name_ward' => 'Hòa Nhơn', "id_district" => "7"],
            ['name_ward' => 'Hòa Ninh', "id_district" => "7"],
            ['name_ward' => 'Hòa Phú', "id_district" => "7"],
            ['name_ward' => 'Hòa Phước', "id_district" => "7"],
            ['name_ward' => 'Hòa Sơn', "id_district" => "7"],
            ['name_ward' => 'Hòa Tiến', "id_district" => "7"],
        ]);
    }
}
