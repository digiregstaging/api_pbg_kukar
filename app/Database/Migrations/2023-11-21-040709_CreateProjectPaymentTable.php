<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProjectPaymentTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'termin' => [
                'type' => 'VARCHAR',
            ],
            'quality_pay' => [
                'type' => 'INT',
            ],
            'fee_pay' => [
                'type' => 'BIGINT',
            ],
            'status' => [
                'type' => 'INT',
            ],
            'project_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('project_payments');
        $this->forge->addForeignKey('project_id', 'projects', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('project_payments');
    }
}
