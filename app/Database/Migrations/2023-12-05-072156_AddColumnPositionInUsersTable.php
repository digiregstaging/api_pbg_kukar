<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnPositionInUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'position' => [
                'type'           => 'VARCHAR',
                'default'          => ""
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('teachers', ["position"]);
    }
}
