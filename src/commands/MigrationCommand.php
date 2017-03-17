<?php

/**
 * This file is part of the Lumineer role & 
 * permission management solution for Lumen.
 *
 * @author Vince Kronlein <vince@19peaches.com>
 * @license https://github.com/19peaches/lumineer/blob/master/LICENSE
 * @copyright 19 Peaches, LLC. All Rights Reserved.
 */

namespace Peaches\Lumineer;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

/**
 * Create migration file for Lumieer tables.
 */
class MigrationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lumineer:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration following the Lumineer specifications.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->laravel->view->addNamespace('Lumineer', substr(__DIR__, 0, -8).'views');

        $rolesTable          = Config::get('lumineer.roles_table');
        $roleUserTable       = Config::get('lumineer.role_user_table');
        $permissionsTable    = Config::get('lumineer.permissions_table');
        $permissionRoleTable = Config::get('lumineer.permission_role_table');

        $this->line('');
        $this->info("Tables: $rolesTable, $roleUserTable, $permissionsTable, $permissionRoleTable");

        $message = $this->generateMigrationMessage(
            $rolesTable,
            $roleUserTable,
            $permissionsTable,
            $permissionRoleTable
        );

        $this->comment($message);
        $this->line('');

        if ($this->confirm("Proceed with the migration creation? [Yes|no]", "Yes")) {
            $this->line('');

            $this->info("Creating migration...");
            if ($this->createMigration($rolesTable, $roleUserTable, $permissionsTable, $permissionRoleTable)) {
                $this->info("Migration successfully created!");
            } else {
                $this->error(
                    "Couldn't create migration.\n Check the write permissions".
                    " within the database/migrations directory."
                );
            }

            $this->line('');
        }
    }

    /**
     * Create the migration.
     *
     * @param string $name
     *
     * @return bool
     */
    protected function createMigration($rolesTable, $roleUserTable, $permissionsTable, $permissionRoleTable)
    {
        $migrationFile = base_path("/database/migrations")."/".date('Y_m_d_His')."_lumineer_setup_tables.php";

        $usersTable  = Config::get('lumineer.users.table') ?: 'users';
        $userModel   = Config::get('lumineer.users.model');
        $userKeyName = (new $userModel())->getKeyName();

        $data = compact(
            'rolesTable',
            'roleUserTable',
            'permissionsTable',
            'permissionRoleTable',
            'usersTable',
            'userKeyName'
        );

        $output = $this->laravel->view->make('Lumineer::generators.migration')->with($data)->render();

        if (!file_exists($migrationFile) && $fs = fopen($migrationFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            return true;
        }

        return false;
    }

    /**
     * Generate the message to display when running the
     * console command showing what tables are going
     * to be created
     * @param  string $rolesTable
     * @param  string $roleUserTable
     * @param  string $permissionsTable
     * @param  string $permissionRoleTable
     * @return string
     */
    public function generateMigrationMessage($rolesTable, $roleUserTable, $permissionsTable, $permissionRoleTable)
    {
        return "A migration that creates '$rolesTable', '$roleUserTable', '$permissionsTable', '$permissionRoleTable'".
        " tables will be created in database/migrations directory";
    }
}
