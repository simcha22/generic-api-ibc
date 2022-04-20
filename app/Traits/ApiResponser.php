<?php

namespace app\Traits;

use Illuminate\Support\Facades\Log;

trait ApiResponser
    {
        protected function rejected($ip, $code): \Illuminate\Http\JsonResponse
        {
            Log::info('api.GeneralAPI - login response: result=REJECTED, message=Invalid Username or password, clientAddress='.$ip);
            return response()->json([
                'clientAddress' => $ip,
                'message' => "Invalid Username or password",
                'otpEnabled' => null,
                'phone' => null,
                'result' => "REJECTED",
                'userGroupId' => null,
                'userId' => 0,
                'userLevel' => null,
                'userType' => null,
                'username' => null,
            ], $code);
        }

        protected function successLogin($ip, $user, $token, $code): \Illuminate\Http\JsonResponse
        {
            Log::info('api.GeneralAPI - login response: result=SUCCESS, clientAddress='.$ip.', userId=' . $user->id);
            return response()->json([
                'clientAddress' => $ip,
                'message' => null,
                'otpEnabled' => $user->otp_enabled,
                'phone' => $user->phone_number,
                'result' => "SUCCESS",
                'userGroupId' => $user->groups->ug_id,
                'userId' => $user->id,
                'userLevel' => $user->user_type,
                'userType' => $user->user_type,
                'username' => $user->user_name,
                'token' => $token
            ], $code);
        }

        protected function successLogout($ip, $code): \Illuminate\Http\JsonResponse
        {

            return response()->json([
                'clientAddress' => $ip,
                'message' => null,
                'result' => "SUCCESS",
            ], $code);
        }

        protected function successGeneric($ip, $data, $time): \Illuminate\Http\JsonResponse
        {
            Log::info('api.InternalAPIUserGroupAccess - generic response: result=SUCCESS. Execution time of generic API in milliseconds: ' . $time);
            return response()->json([
                'clientAddress' => $ip,
                'columnList' => (is_array($data)? array_keys(get_object_vars($data[0])): null),
                'data' => (is_array($data) ? $data: null),
                'message' => null,
                'result' => "SUCCESS",
                'rowsAffected' => 0,
            ], 200);
        }

        protected function failedGeneric($ip, $time): \Illuminate\Http\JsonResponse
        {
            Log::info('api.InternalAPIUserGroupAccess - generic response: result=FAILED, message=Failed to run query. Please see log for more information. Execution time of generic API in milliseconds: '. $time);
            return response()->json([
                'clientAddress' => $ip,
                'columnList' => null,
                'data' => null,
                'message' => 'Failed to run query. Please see log for more information',
                'result' => 'FAILED',
                'rowsAffected' => 0,
            ], 200);
        }

        protected function failedValidation($ip, $time): \Illuminate\Http\JsonResponse
        {
            Log::info('api.InternalAPIUserGroupAccess - generic response: result=FAILED, message=Validation error on the data . Please see log for more information. Execution time of generic API in milliseconds: '. $time);
            return response()->json([
                'clientAddress' => $ip,
                'columnList' => null,
                'data' => null,
                'message' => 'Validation error on the data. Please see log for more information',
                'result' => 'FAILED',
                'rowsAffected' => 0,
            ], 422);
        }

    }
