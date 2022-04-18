<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenericApiOpen extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_name',
        'query_type',
        'query_syntax',
    ];

    protected $table = 't_adm_open_generic_api_conf';

    public $timestamps = false;
}
