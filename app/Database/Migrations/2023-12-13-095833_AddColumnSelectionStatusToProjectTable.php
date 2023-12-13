<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnSelectionStatusToProjectTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('projects', [
            'selection_status' => [
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
