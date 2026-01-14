<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class InspectDB extends BaseCommand
{
    protected $group = 'Fix';
    protected $name = 'fix:inspect';
    protected $description = 'Inspects tables and constraints.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        $tables = $db->listTables();
        CLI::write("Tables: " . implode(', ', $tables), 'cyan');
        
        // Inspect legacy_users constraints
        if ($db->tableExists('legacy_users')) {
            $query = "SELECT conname, contype FROM pg_constraint join pg_class on pg_constraint.conrelid = pg_class.oid where relname = 'legacy_users'";
            $constraints = $db->query($query)->getResultArray();
            CLI::write("Constraints on legacy_users:", 'yellow');
            foreach ($constraints as $c) {
                CLI::write(" - " . $c['conname'] . " (" . $c['contype'] . ")");
            }
        }

        // Inspect users constraints if exists
        if ($db->tableExists('users')) {
            $query = "SELECT conname, contype FROM pg_constraint join pg_class on pg_constraint.conrelid = pg_class.oid where relname = 'users'";
            $constraints = $db->query($query)->getResultArray();
            CLI::write("Constraints on users:", 'yellow');
            foreach ($constraints as $c) {
                CLI::write(" - " . $c['conname'] . " (" . $c['contype'] . ")");
            }
        }
        
        // Check for pk_users specifically anywhere
        $query = "SELECT conname, relname FROM pg_constraint join pg_class on pg_constraint.conrelid = pg_class.oid where conname = 'pk_users'";
        $result = $db->query($query)->getResultArray();
        if (!empty($result)) {
             CLI::write("Found 'pk_users' on table: " . $result[0]['relname'], 'red');
        } else {
             CLI::write("'pk_users' constraint NOT found.", 'green');
        }
    }
}
