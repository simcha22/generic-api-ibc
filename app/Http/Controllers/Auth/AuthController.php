<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\ExecSmsOtpApi;

class AuthController extends Controller
{
    use ApiResponser;

    public  function store(LoginRequest $request, ExecSmsOtpApi $execSmsOtpApi)
    {
        // Check user and password
        $user = User::with('groups')->where('user_name', $request->username)
            ->where('password', md5($request->username . $request->password))
            ->where('blocked', '0')
            ->first();

        Log::info('api.GeneralAPI - login request: username='.$request->username.', clientAddress='.$request->ip());
        if (!$user) {
            Log::debug('control.WSGeneralAPIMgr - Invalid password for user');
            return $this->rejected($request->ip(), 200);
        }

        if($execSmsOtpApi->sendSms($user)){
            return $this->responseOtpSuccess($request->ip(), 201);
        }else{
            Log::debug('control.WSGeneralAPIMgr - not send sms');
            return $this->rejected($request->ip(), 200);
        }
    }

    public function logout(Request $request)
    {
        Log::info('api.GeneralAPI - logout request: userId=' .$request->user()->id);

        $request->user()->currentAccessToken()->delete();
        return $this->successLogout($request->ip(), 200);
    }

    public function checkOTP(Request $request,  ExecSmsOtpApi $execSmsOtpApi){

        $user = User::with('groups')->where('user_name', $request->username)
            ->where('password', md5($request->username . $request->password))
            ->where('blocked', '0')
            ->first();

        Log::info('api.GeneralAPI - login request otp: username='.$request->username.', clientAddress='.$request->ip());
        if (!$user) {
            Log::debug('control.WSGeneralAPIMgr - Invalid password for user at otp');
            return $this->rejected($request->ip(), 200);
        }

        if(!$execSmsOtpApi->checkSms($request, $user)){
            Log::debug('control.WSGeneralAPIMgr - Invalid code for user at otp');
            return $this->rejected($request->ip(), 200);
        }else{
            $token = $user->createToken('genericApiToken')->plainTextToken;
            return $this->successLogin($request->ip(), $user, $token, 201);
        }
    }
}
