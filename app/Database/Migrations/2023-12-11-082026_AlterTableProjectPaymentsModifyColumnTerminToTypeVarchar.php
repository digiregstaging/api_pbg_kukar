<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableProjectPaymentsModifyColumnTerminToTypeVarchar extends Migration
{
    public function up()
    {
        $alterfields = [
            'termin' => [
                'type' => 'VARCHAR',
            ],
            "quality_pay" => [
                "type" => "DECIMAL",
                'constraint' => '3,1',
            ]
        ];

        $this->forge->modifyColumn('project_payments', $alterfields);
    }

    public function down()
    {
        $alterfields = [
            'termin' => [
                'type' => 'INT',
            ],
            "quality_pay" => [
                "type" => "INT",
            ],
        ];

        $this->forge->modifyColumn('project_payments', $alterfields);
    }
}
