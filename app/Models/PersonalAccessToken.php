<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{

    protected $dates = ['last_used_at'];

     public function getDateFormat()
     {
         return 'd/m/Y h:i:s';
     }
}
