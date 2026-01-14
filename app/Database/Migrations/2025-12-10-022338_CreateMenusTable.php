<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMenusTable extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('menus')) {
            // Create table using raw SQL for better PostgreSQL compatibility
            $this->db->query("
                CREATE TABLE menus (
                    id SERIAL PRIMARY KEY,
                    parent_id INTEGER NULL,
                    label VARCHAR(255) NOT NULL,
                    url VARCHAR(255) NULL,
                    icon VARCHAR(50) NULL,
                    permission VARCHAR(100) NULL,
                    type VARCHAR(20) NOT NULL DEFAULT 'item',
                    order_position INTEGER NOT NULL DEFAULT 0,
                    is_active BOOLEAN NOT NULL DEFAULT true,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            
            // Add foreign key constraint for parent_id
            $this->db->query('
                ALTER TABLE menus 
                ADD CONSTRAINT fk_menus_parent 
                FOREIGN KEY (parent_id) 
                REFERENCES menus(id) 
                ON DELETE CASCADE
            ');
            
            // Add comment on type column
            $this->db->query("
                COMMENT ON COLUMN menus.type IS 'section, group, or item'
            ");
        }
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS menus CASCADE');
    }
}
