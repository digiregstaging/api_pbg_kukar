<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProgramTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'program' => [
                'type' => 'VARCHAR',
            ],
            'activity' => [
                'type' => 'VARCHAR',
            ],
            'sub_activity' => [
                'type' => 'VARCHAR',
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type'       => 'DATETIME',
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('programs');
    }

    public function down()
    {
        $this->forge->dropTable('programs');
    }
}
