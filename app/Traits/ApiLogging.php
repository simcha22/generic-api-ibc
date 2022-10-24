<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\AuditLog;

trait ApiLogging{

    protected function genericRequest($request, $type){

        if($type == 'generic'){
            $user_id = Auth::user()->id;
        }else if($type == 'open'){
            $user_id = 0;
        }

        if(request('sqlwildcards'))
            $sqlwilcardText = ', sqlwildcards=[' .implode(',',$request->sqlwildcards) . ']';
        else
        $sqlwilcardText = '';

        Log::info('api.InternalAPIUserGroupAccess - generic request: userId=' .$user_id . ', clientAddress=' . $request->ip(). ', apiName=' . $request->apiName . ', wildcards=[' . implode(',',$request->wildcards) . ']' . $sqlwilcardText);
        return $this->InsertAubitLog($request, $type, $user_id);
    }

    protected function InsertAubitLog($request, $type, $user_id){

        if(request('sqlwildcards'))
            $sqlwilcardText = ', "sqlwildcards": ["' .implode('","',$request->sqlwildcards) . '"]';
        else
            $sqlwilcardText = '';


       return AuditLog::create([
            'request_time' => now(),
            'request_type' => $type == 'open'? 18: 10,
            'request_state' => 1,
            'user_id' => $user_id,
            'params' => '{"apiName":"'.$request->apiName.'", "wildcards": ["'.implode('","',$request->wildcards).'"]' . $sqlwilcardText .'}',
            'client_address' => $request->ip(),
            'response' => '{}',
        ]);
    }

    protected function genericRunQuery($query, $apiName){
        Log::debug('Run query for api ' . $apiName);
        Log::debug('Replace Wildcards. query=' . $query);
    }

    protected function genericResponse($response): void
    {
        Log::info(' api.InternalAPIUserGroupAccess - generic response: result=SUCCESS.');
    }

    protected function genericNotFindError($auditId) : void
    {
        log::error('Could not find generic');

        AuditLog::where('id', $auditId)->update([
            'request_state' => 2,
            'response' => null,
            'error_msg'=> 'Could not find generic. Please see log for more information',
        ]);
    }

    protected function genericNotRunError($auditId){
        log::error('Failed to run query. Please see log for more information');

        AuditLog::where('id', $auditId)->update([
            'request_state' => 2,
            'response' => null,
            'error_msg'=> 'Failed to run query. Please see log for more information',
        ]);
    }

    protected function dashboardLogging($ip, $ids): void
    {
        Log::info('api.DashboardAPI - getDashboardData request: userId='.Auth::user()->id.', clientAddress='.$ip.', ids=['.implode(',',$ids).']');
    }
}
