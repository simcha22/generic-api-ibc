<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'request_time',
        'request_type',
        'request_state',
        'user_id',
        'params',
        'client_address',
        'response',
        'error_msg',
        ];

    protected $table = 'pa_appsrv_audit_log';

    public $timestamps = false;
}
