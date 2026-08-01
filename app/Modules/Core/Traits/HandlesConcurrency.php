<?php

namespace App\Modules\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait HandlesConcurrency
{
    /**
     * Safely applies lockForUpdate() only on database drivers supporting row locks (MySQL/PostgreSQL).
     */
    protected function safeLockForUpdate(Builder $query): Builder
    {
        $driver = DB::connection()->getDriverName();

        if (in_array($driver, ['mysql', 'pgsql', 'sqlsrv'], true)) {
            return $query->lockForUpdate();
        }

        return $query;
    }
}
