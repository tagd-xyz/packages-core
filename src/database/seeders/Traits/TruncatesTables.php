<?php

namespace Tagd\Core\Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;

trait TruncatesTables
{
    /**
     * Truncate tables
     *
     * @return void
     */
    private function truncate(array $tables)
    {
        $mysql = 'mysql' == DB::connection()->getConfig()['driver'];

        if ($mysql) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        if ($mysql) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
