<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableVendorHistory extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'task_name' => [
                'type' => 'VARCHAR',
            ],
            'status' => [
                'type' => 'INT',
            ],
            'vendor_id' => [
                'type' => 'INT',
                'unsigned' => true,
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
        $this->forge->createTable('vendor_histories');
        $this->forge->addForeignKey('vendor_id', 'vendors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('project_id', 'projects', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('vendor_histories');
    }
}
