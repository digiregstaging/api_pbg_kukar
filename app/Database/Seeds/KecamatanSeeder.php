<?php

namespace App\Database\Seeds;

use App\Models\Kecamatan;
use CodeIgniter\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    public function run()
    {
        $list_kecamatan = [
            "Anggana",
            "Kembang Janggut",
            "Kenohan",
            "Kota Bangun",
            "Kota Bangun Darat",
            "Loa Janan",
            "Loa Kulu",
            "Marang Kayu",
            "Muara Badak",
            "Muara Jawa",
            "Muara Kaman",
            "Muara Muntai",
            "Muara Wis",
            "Samboja",
            "Samboja Barat",
            "Sanga Sanga",
            "Sebulu",
            "Tabang",
            "Tenggarong",
            "Tenggarong Seberang"
        ];
        $model = new Kecamatan();

        foreach ($list_kecamatan as $key => $value) {
            $data = [
                "name" => $value,
            ];

            $model->save($data);
        }
    }
}
