<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnProgressAndStatusOnTableProjectProgress extends Migration
{
    public function up()
    {
        $this->forge->addColumn('project_progress', [
            'status' => [
                'type'           => 'INT',
                'default'          => 1
            ],
            "progress_in_percent" => [
                "type" => "DECIMAL",
                'constraint' => '3,1',
                "default" => 0
            ],
        ]);

        $alterfields = [
            'step' => [
                'type' => 'VARCHAR',
            ],
            'quality' => [
                "type" => "DECIMAL",
                'constraint' => '3,1',
            ]
        ];

        $this->forge->modifyColumn('project_progress', $alterfields);
    }

    public function down()
    {
        $this->forge->dropColumn('project_progress', ["status", "progress_in_percent"]);
        $alterfields = [
            'step' => [
                'type' => 'INT',
            ],
            'quality' => [
                "type" => "INT",
            ]
        ];

        $this->forge->modifyColumn('project_progress', $alterfields);
    }
}
