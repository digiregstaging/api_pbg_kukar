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
                'type' => 'INT',
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
        $this->db->query("CREATE INDEX project_payments_termin_index ON project_payments(termin)");
        $this->db->query("CREATE INDEX project_payments_status_index ON project_payments(status)");
    }

    public function down()
    {
        $this->forge->dropTable('project_payments');
    }
}
