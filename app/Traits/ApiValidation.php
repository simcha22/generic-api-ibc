<?php

namespace app\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


trait ApiValidation
{
    protected function genericApiValidation($request): bool
    {
        $validator = Validator::make($request->all() ,[
//            'username' => ['required', 'string', 'exists:pa_users,user_name'],
//            'password' => ['required', 'string'],
            'apiName' => ['required', 'string', 'exists:t_adm_generic_api_conf,api_name'],
            'wildcards' => ['array'],
        ]);

        if ($validator->fails()) {
            Log::debug('Validation error on the data');
            Log::info($validator->getMessageBag());
            return false;
        }else{
            return true;
        }
    }

    protected function genericApiWithSqlValidation($request): bool
    {
        $validator = Validator::make($request->all() ,[
//            'username' => ['required', 'string', 'exists:pa_users,user_name'],
//            'password' => ['required', 'string'],
            'apiName' => ['required', 'string', 'exists:t_adm_generic_api_conf,api_name'],
            'wildcards' => ['array'],
            'sqlwildcards' => ['array'],
        ]);

        if ($validator->fails()) {
            Log::debug('Validation error on the data');
            Log::info($validator->getMessageBag());
            return false;
        }else{
            return true;
        }
    }

    protected function genericApiOpenValidation($request): bool
    {
        $validator = Validator::make($request->all() ,[
            'apiName' => ['required', 'string', 'exists:t_adm_open_generic_api_conf,api_name'],
            'wildcards' => ['array'],
        ]);

        if ($validator->fails()) {
            Log::debug('Validation error on the data');
            Log::info($validator->getMessageBag());
            return false;
        }else{
            return true;
        }
    }

    protected function genericApiWithSqlOpenValidation($request): bool
    {
        $validator = Validator::make($request->all() ,[
            'apiName' => ['required', 'string', 'exists:t_adm_open_generic_api_conf,api_name'],
            'wildcards' => ['array'],
            'sqlwildcards' => ['array'],
        ]);

        if ($validator->fails()) {
            Log::debug('Validation error on the data');
            Log::info($validator->getMessageBag());
            return false;
        }else{
            return true;
        }
    }
}
