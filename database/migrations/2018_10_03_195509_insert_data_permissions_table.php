<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertDataPermissionsTable extends Migration
{
    /**
     * Insert data to the permissions table.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert([
            ['name' => 'Create Posts'],
            ['name' => 'Manage All Posts'],
            ['name' => 'Manage All Comments'],
            ['name' => 'Create And Manage Categories'],
            ['name' => 'Manage Tags'],
            ['name' => 'Manage Users'],
        ]);
    }

    /**
     * Delete data from permissions table.
     *
     * @return void
     */
    public function down()
    {
        DB::table('Permission')->truncate();
    }
}
