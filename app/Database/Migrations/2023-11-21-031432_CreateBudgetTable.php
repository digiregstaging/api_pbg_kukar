<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBudgetTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'source' => [
                'type' => 'VARCHAR',
            ],
            'year' => [
                'type' => 'DATETIME',
            ],
            'value' => [
                'type' => 'BIGINT',
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
        $this->forge->createTable('budgets');
        $this->db->query("CREATE INDEX budgets_value_index ON budgets(value)");
        $this->db->query("CREATE INDEX budgets_year_index ON budgets(year)");
    }

    public function down()
    {
        $this->forge->dropTable('budgets');
    }
}
