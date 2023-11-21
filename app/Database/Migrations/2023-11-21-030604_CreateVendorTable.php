<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVendorTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'vendor_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'director' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'address' => [
                'type'       => 'TEXT',
            ],
            'kbli_code' => [
                'type'       => 'VARCHAR',
                'unique'       => true,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'unique' => true
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'unique' => true
            ],
            'npwp' => [
                'type' => 'VARCHAR',
                "unique" => true
            ],
            'created_at' => [
                'type'       => 'DATETIME',
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('vendors');
        $this->db->query("CREATE INDEX vendors_email_index ON vendors(email)");
        $this->db->query("CREATE INDEX vendors_kbli_code_index ON vendors(kbli_code)");
        $this->db->query("CREATE INDEX vendors_npwp_index ON vendors(npwp)");
        $this->db->query("CREATE INDEX vendors_phone_index ON vendors(phone)");
    }

    public function down()
    {
        $this->forge->dropTable('vendors');
    }
}
