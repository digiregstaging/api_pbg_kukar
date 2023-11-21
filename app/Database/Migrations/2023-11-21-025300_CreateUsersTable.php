<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique' => TRUE
            ],
            'password' => [
                'type' => 'TEXT',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'job' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'role' => [
                'type'       => 'INT',
                'constraint' => 3,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'unique' => true
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'unique' => true
            ],
            'token' => [
                'type' => 'TEXT',
                "null" => true
            ],
            'created_at' => [
                'type'       => 'DATETIME',
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
        $this->db->query("CREATE INDEX users_email_index ON users(email)");
        $this->db->query("CREATE INDEX users_role_index ON users(role)");
        $this->db->query("CREATE INDEX users_phone_index ON users(phone)");
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
