<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableProjectProgressModifyColumnProgressInPercent extends Migration
{
    public function up()
    {
        $alterfields = [
            'progress_in_percent' => [
                'name' => "progress",
                'type' => 'INT',
            ],
        ];

        $this->forge->modifyColumn('project_progress', $alterfields);
    }

    public function down()
    {
        $alterfields = [
            'progress_in_percent' => [
                'name' => "progress",
                'type' => 'DECIMAL',
            ],
        ];

        $this->forge->modifyColumn('project_progress', $alterfields);
    }
}
