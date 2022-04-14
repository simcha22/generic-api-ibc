<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenericApi extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_name',
        'query_type',
        'query_syntax',
        'data_source_name',
        'user_group_based',
        'allowed_by_admin'
    ];
    protected $table = 't_adm_generic_api_conf';
}
