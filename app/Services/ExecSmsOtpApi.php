<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\OtpCode;

class ExecSmsOtpApi
{
    public function sendSms($user){
        if($user->phone_number){
            $randomNumber = random_int(100000, 999999);

            $response = Http::withHeaders([
                'Content-Type' => 'application/xml',
            ])->post('https://api.inforu.co.il/SendMessageXml.ashx?InforuXML=<Inforu><User><Username>Ibc_sms</Username><ApiToken>r5jm0fdxeznq69ns61u166tzx</ApiToken></User><Content Type="sms"><Message>'.$randomNumber.'</Message></Content><Recipients><PhoneNumber>'.$user->phone_number.'</PhoneNumber></Recipients><Settings><Sender>MOM</Sender></Settings></Inforu>');

           $code = OtpCode::create([
                'code' => $randomNumber,
                'expiration_time' => now()->addMinutes(5),
                'user_id' => $user->id,
            ]);
           if($code && $response)
            return true;
           else
               return false;
        }else
            return false;
    }

    public function checkSms($request, $user){

       $code = OtpCode::query()
            ->where([
                ['code', $request->code],
                ['user_id', $user->id],
                ['expiration_time', '>', now()]])
            ->first();
       if($code)
           return true;
       else
           return false;

    }
}
