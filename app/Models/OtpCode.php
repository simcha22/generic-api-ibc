<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'expiration_time',
        'user_id',
    ];

    protected $table = 't_otp_code_2_user';

    public $timestamps = false;
}
