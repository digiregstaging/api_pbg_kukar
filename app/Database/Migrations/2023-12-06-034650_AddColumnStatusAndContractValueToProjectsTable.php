<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnStatusAndContractValueToProjectsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('projects', [
            'status' => [
                'type'           => 'INT',
                'default'          => 0
            ],
            "contract_value" => [
                "type" => "INT",
                "default" => 0
            ],
            "project_code" => [
                "type" => "VARCHAR",
                "default" => ""
            ],
            "pugu" => [
                "type" => "VARCHAR",
                "default" => ""
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('projects', ["status", "contract_value", "project_code", "pugu"]);
    }
}
