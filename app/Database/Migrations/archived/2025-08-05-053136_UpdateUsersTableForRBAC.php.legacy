<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUsersTableForRBAC extends Migration
{
    public function up()
    {
        // Tambah kolom baru
        $fields = [
            'role_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'last_login' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ];

        $this->forge->addColumn('users', $fields);

        // Tambahkan foreign key (pakai db->query karena forge belum support ALTER table foreign key di PostgreSQL)
        $this->db->query('ALTER TABLE "users" ADD CONSTRAINT "users_role_id_foreign" FOREIGN KEY ("role_id") REFERENCES "roles"("id") ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        // Hapus foreign key
        $this->db->query('ALTER TABLE "users" DROP CONSTRAINT IF EXISTS "users_role_id_foreign"');

        // Hapus kolom
        $this->forge->dropColumn('users', ['role_id', 'is_active', 'last_login']);
    }
}
