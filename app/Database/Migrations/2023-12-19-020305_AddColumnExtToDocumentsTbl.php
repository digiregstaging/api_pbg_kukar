<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnExtToDocumentsTbl extends Migration
{
    public function up()
    {
        $this->forge->addColumn('documents_tbl', [
            'ext' => [
                'type'           => 'VARCHAR',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        //
    }
}
