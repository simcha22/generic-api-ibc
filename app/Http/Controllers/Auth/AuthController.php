<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use ApiResponser;

    public  function store(LoginRequest $request)
    {
        // Check user and password
        $user = User::with('groups')->where('user_name', $request->user_name)
            ->where('password', md5($request->user_name . $request->password))
            ->where('blocked', '0')
            ->first();

        Log::info('api.GeneralAPI - login request: username='.$request->user_name.', clientAddress='.$request->ip());
        if (!$user) {
            Log::debug('control.WSGeneralAPIMgr - Invalid password for user simcha');
            return $this->rejected($request->ip(), 200);
        }

        $token = $user->createToken('genericApiToken')->plainTextToken;

        return $this->successLogin($request->ip(), $user, $token, 201);
    }

    public function logout(Request $request)
    {
        Log::info('api.GeneralAPI - logout request: userId=' .$request->user()->id);

        $request->user()->currentAccessToken()->delete();
        return $this->successLogout($request->ip(), 200);
    }
}
