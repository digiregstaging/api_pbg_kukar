<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableProgressPaymentAddDefaultValueToColumnStatusToProjectPaymentTable extends Migration
{
    public function up()
    {
        // $this->forge->dropColumn('project_payments', [
        //     "status",
        // ]);

        $this->forge->addColumn('project_payments', [
            'status' => [
                'type'           => 'INT',
                'null' => true,
                'default'          => 1,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('project_payments', [
            "status",
        ]);
    }
}
