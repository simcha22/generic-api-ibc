<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\groups;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExecGenericApi
{
    public function makeQuery($wildcards, $query_syntax){
        if(!empty($wildcards)) {
            foreach ($wildcards as $kye => $wildcard) {
                $query_syntax = str_replace('[WILDCARD_' . ++$kye . ']', "'". $wildcard . "'", $query_syntax);
            }
        }
        return $query_syntax;
    }

    public function makeQueryWithSql($sqlwildcards, $query){

        if(!empty($sqlwildcards)) {
            foreach ($sqlwildcards as $kye => $sqlwildcard) {
                $query = str_replace('[SQLWILDCARD_' . ++$kye . ']', $sqlwildcard, $query);
            }
        }
        return $query;
    }

    public function userGroupToQuery($query, $ug_id, $auditId){
        try {
            $group = groups::where('u_id',Auth::user()->id)->first();
            if($ug_id){
                $query = str_replace('[UG_ID]', $group->ug_id, $query);
            }
            return $query;
        }catch (\Exception $err ){
            log::error('error Could not find user_group');
            log::error($err);

            AuditLog::where('id', $auditId)->update([
                'request_state' => 2,
                'response' => null,
                'error_msg'=> 'Could not find user_group for this api ',
            ]);
            return $query;
        }
    }

    public function runQuery($query, $type, $auditId): array|bool|int
    {
        try {
            return match ($type) {
                "SELECT" => DB::select($query),
                "INSERT" => DB::insert($query),
                "UPDATE" => DB::update($query),
                default => [],
            };
        }catch (\Exception $err ){
            log::error('Failed to run query');
            log::error($err);

            AuditLog::where('id', $auditId)->update([
                'request_state' => 2,
                'response' => null,
                'error_msg'=> 'Failed to run query. Please see log for more information',
            ]);
            return false;
        }
    }


}
