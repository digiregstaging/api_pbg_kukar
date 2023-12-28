<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnOtpToUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'otp' => [
                'type'           => 'VARCHAR',
                'null' => true,
            ],
            'expired_otp' => [
                'type'           => 'INT',
                'null' => true,
            ],
            'attempt_to_verify' => [
                'type'           => 'INT',
                'null' => true,
            ]
        ]);

        $this->db->query("CREATE INDEX users_otp_index ON users(otp)");
        $this->db->query("CREATE INDEX users_expired_otp_index ON users(expired_otp)");
        $this->db->query("CREATE INDEX users_attempt_to_verify_index ON users(attempt_to_verify)");
    }

    public function down()
    {
        //
    }
}
