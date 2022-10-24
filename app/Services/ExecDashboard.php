<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExecDashboard
{
    public function runQuery($query, $api_object, $name) : array|bool|null|string
    {
        try {
            return match ($api_object) {
               '1' => DB::select($query),
               '2' => Cache::get($name),
               default => null,
            };
        }catch (\Exception $err ){
            log::error('Failed to run query');
            log::error($err);

            return false;
        }
    }
}
