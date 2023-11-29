<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableProgressDetail extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'detail_pekerjaan' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type' => 'VARCHAR',
            ],
            'pic' => [
                'type' => 'VARCHAR',
            ],
            'project_progress_id' => [
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
        $this->forge->createTable('progress_details');
        $this->forge->addForeignKey('project_progress_id', 'project_progress', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('progress_details');
    }
}
