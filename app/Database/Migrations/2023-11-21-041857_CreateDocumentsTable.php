<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDocumentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'doc_name' => [
                'type' => 'VARCHAR',
            ],
            'url' => [
                'type' => 'TEXT',
            ],
            'type' => [
                'type' => 'VARCHAR',
            ],
            'additional_data_id' => [
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
        $this->forge->createTable('documents_tbl');
        $this->db->query("CREATE INDEX additional_data_id_index ON documents_tbl(additional_data_id)");
        $this->db->query("CREATE INDEX type_index ON documents_tbl(type)");
    }

    public function down()
    {
        $this->forge->dropTable('documents_tbl');
    }
}
