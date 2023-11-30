<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableProjectProgress extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'step' => [
                'type' => 'INT',
            ],
            'quality' => [
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
        $this->forge->createTable('project_progress');
        $this->forge->addForeignKey('project_id', 'projects', 'id', 'CASCADE', 'CASCADE');
        $this->db->query("CREATE INDEX project_progress_step_index ON project_progress(step)");
    }

    public function down()
    {
        $this->forge->dropTable('project_progress');
    }
}
