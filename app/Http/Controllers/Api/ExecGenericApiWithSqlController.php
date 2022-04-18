<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ExecGenericApiWithSqlRequest;
use App\Models\GenericApi;
use App\Services\ExecGenericApi;
use App\Traits\ApiLogging;
use Illuminate\Http\Request;

class ExecGenericApiWithSqlController extends Controller
{
    use ApiLogging;

    public function index(ExecGenericApiWithSqlRequest $request, ExecGenericApi $execGenericApi){

        $auditLog = $this->genericRequest($request, 'generic');

        // get the generic from t_adm_generic_api_conf table
        $generic = GenericApi::where('api_name', $request->apiName)->first();
        if(!$generic) {
            $this->genericNotFindError($auditLog->id);
            return;
        }

        // replace wildcards into the query
        $query = $execGenericApi->makeQuery($request->wildcards, $generic->query_syntax);

        // replace sql wildcards into the query
        $query = $execGenericApi->makeQueryWithSql($request->sqlwildcards, $query);

        // replace user group if exists
        $query = $execGenericApi->userGroupToQuery($query, $generic->user_group_based, $auditLog->id);

        $this->genericRunQuery($query, $request->apiName);

        // run the query into database
        $result = $execGenericApi->runQuery($query, $generic->query_type, $auditLog->id);

        // make response ..
        dd($result);
    }
}