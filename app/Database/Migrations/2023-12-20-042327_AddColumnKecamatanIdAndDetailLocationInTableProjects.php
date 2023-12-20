<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnKecamatanIdAndDetailLocationInTableProjects extends Migration
{
    public function up()
    {
        $this->forge->addColumn('projects', [
            'detail_location' => [
                'type'           => 'text',
                'null' => true,
            ],
            'kecamatan_id' => [
                'type'           => 'INT',
                'null' => true,
            ],
        ]);

        $this->db->query("CREATE INDEX projects_kecamatan_id_index ON projects(kecamatan_id)");
    }

    public function down()
    {
        //
    }
}
