<?php

/**
 * Table untuk menyimpan Log Activity
 * 
 * - id: ID log activity
 * - route: Route yang diakses
 * - method: Method yang digunakan
 * - is_ajax: Apakah request adalah AJAX
 * - create_date: Tanggal dan waktu log activity
 * - id_user: ID user yang melakukan log activity
 * - active: Apakah log activity masih aktif
 * - data: Data yang dikirimkan
 * - platform: Platform yang digunakan
 * - browser: Browser yang digunakan
 * 
 * source: https://chatgpt.com/s/t_691e2cdbfb248191bcf073622630ee95
 * */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserAppsActivity extends Migration
{
    public function up()
    {
        // PostgreSQL identity column is handled by autoIncrement = true
        $this->forge->addField([
            'id' => [
                'type'           => 'SMALLINT',
                'auto_increment' => true,
                'null'           => false,
            ],
            'route' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'method' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'is_ajax' => [
                'type'       => 'CHAR',
                'constraint' => 1,
                'null'       => true,
            ],
            'create_date' => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
            'id_user' => [
                'type' => 'INT',
                'null' => false,
            ],
            'active' => [
                'type'    => 'SMALLINT',
                'default' => 1,
                'null'    => false,
            ],
            'data' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'platform' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
            ],
            'browser' => [
                'type'       => 'VARCHAR',
                'constraint' => 32,
                'null'       => true,
            ],
        ]);

        // Primary key
        $this->forge->addKey('id', true);

        // Foreign Key id_user -> users.id
        $this->forge->addForeignKey('id_user', 'users', 'id', 'CASCADE', 'CASCADE');

        // Create table
        $this->forge->createTable('user_apps_activity');
    }

    public function down()
    {
        $this->forge->dropTable('user_apps_activity');
    }
}
