<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ExecGenericApiOpenRequest;
use App\Models\GenericApiOpen;
use App\Services\ExecGenericApi;
use App\Traits\ApiLogging;
use Illuminate\Http\Request;

class ExecGenericApiOpenController extends Controller
{
    use ApiLogging;

    public function index(ExecGenericApiOpenRequest $request, ExecGenericApi $execGenericApi){

        // log info generic
        $auditLog = $this->genericRequest($request, 'open');

        // get the generic from t_adm_generic_api_conf table
            $generic = GenericApiOpen::where('api_name', $request->apiName)->first();
            if(!$generic) {
                $this->genericNotFindError($auditLog->id);
                return;
            }

        // replace wildcards into the query
        $query = $execGenericApi->makeQuery($request->wildcards, $generic->query_syntax);

        // replace user group if exists
        // please Check if Do you need it in open generic api
        //$query = $execGenericApi->userGroupToQuery($query, $generic->user_group_based);

        // log debug query
        $this->genericRunQuery($query, $request->apiName);

        // run the query into database
        $result = $execGenericApi->runQuery($query, $generic->query_type, $auditLog->id);

        // make response ..
        dd($result);
    }
}
