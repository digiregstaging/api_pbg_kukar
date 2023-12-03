<?php

namespace App\Database\Seeds;

use App\Models\User;
use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $model = new User();

        $data = [
            "username" => "spv1",
            "password" => password_hash("12345678", PASSWORD_DEFAULT),
            "name" => "Supervisor",
            "job" => "job",
            "phone" => "+6281378610993",
            "email" => "email@mail.com",
            "role" => 1,
        ];

        $model->save($data);
    }
}
