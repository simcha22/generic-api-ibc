<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ExecGenericApiRequest;
use App\Models\GenericApi;
use Illuminate\Http\Request;
use App\Services\ExecGenericApi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Traits\ApiLogging;

class ExecGenericApiController extends Controller
{
    use ApiLogging;

    public function index(ExecGenericApiRequest $request, ExecGenericApi $execGenericApi){

      $auditLog = $this->genericRequest($request, 'generic');

      // get the generic from t_adm_generic_api_conf table
      $generic = GenericApi::where('api_name', $request->apiName)->first();
        if(!$generic) {
            $this->genericNotFindError($auditLog->id);
            return;
        }

      // replace wildcards into the query
      $query = $execGenericApi->makeQuery($request->wildcards, $generic->query_syntax);

      // replace user group if exists
      $query = $execGenericApi->userGroupToQuery($query, $generic->user_group_based, $auditLog->id);

      $this->genericRunQuery($query, $request->apiName);

      // run the query into database
      $result = $execGenericApi->runQuery($query, $generic->query_type, $auditLog->id);

      // make response ..
      dd($result);

        //dd($auditLog->id);
    }
}
