<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRbacColumnsToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'role_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'last_login' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];

        $this->forge->addColumn('users', $fields);

        // Add foreign key constraint for role_id
        // Using explicit SQL for better compatibility and control, especially with Postgres
        $this->db->query('ALTER TABLE "users" ADD CONSTRAINT "users_role_id_foreign" FOREIGN KEY ("role_id") REFERENCES "roles"("id") ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        // Drop foreign key
        $this->db->query('ALTER TABLE "users" DROP CONSTRAINT IF EXISTS "users_role_id_foreign"');

        // Drop columns
        $this->forge->dropColumn('users', ['role_id', 'last_login']);
    }
}
