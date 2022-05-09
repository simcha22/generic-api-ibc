<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ExecGenericApiOpenRequest;
use App\Models\GenericApiOpen;
use App\Services\ExecGenericApi;
use App\Traits\ApiLogging;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Traits\ApiValidation;
use Illuminate\Support\Facades\Log;

class ExecGenericApiOpenController extends Controller
{
    use ApiLogging, ApiResponser, ApiValidation;

    public function index(Request $request, ExecGenericApi $execGenericApi): \Illuminate\Http\JsonResponse
    {
        $startTime = microtime(true);

        if(!$this->genericApiOpenValidation($request)){
            return $this->failedValidation($request->ip(), number_format(microtime(true) - $startTime));
        }
        // log info generic

        $auditLog = $this->genericRequest($request, 'open');

        // get the generic from t_adm_generic_api_conf table
        $generic = GenericApiOpen::where('api_name', $request->apiName)->first();
        if(!$generic) {
            $this->genericNotFindError($auditLog->id);
            return $this->failedGeneric($request->ip(), number_format(microtime(true) - $startTime));
        }

        // replace wildcards into the query
        $query = $execGenericApi->makeQuery($request->wildcards, $generic->query_syntax);

        // log debug query
        $this->genericRunQuery($query, $request->apiName);

        // run the query into database
        $result = $execGenericApi->runQuery($query, $generic->query_type, $auditLog->id);

        // make response ..
        // if(is_array($result)){}
        if($result) {
            return $this->successGeneric($request->ip(), $result, number_format(microtime(true) - $startTime));
        }elseif (empty($result)){
            return $this->successGeneric($request->ip(), $result, number_format(microtime(true) - $startTime));
        }
        else{
            $this->genericNotRunError($auditLog->id);
            return $this->failedGeneric($request->ip(), number_format(microtime(true) - $startTime));
        }
    }
}
