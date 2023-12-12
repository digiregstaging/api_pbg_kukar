<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDefaultValueToProjectTable extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('projects', [
            "status",
        ]);

        $this->forge->addColumn('projects', [
            'status' => [
                'type'           => 'INT',
                'null' => true,
                'default'          => 1,
            ],
        ]);
    }

    public function down()
    {
        //
    }
}
