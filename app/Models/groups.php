<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class groups extends Model
{
    use HasFactory;

    protected $fillable = [
        'u_id',
        'ug_id',
    ];

    protected $primaryKey = 'ug_gid';

    public $timestamps = false;

    protected $table = 'pa_user_to_groups';

    public function user()
    {
        return $this->belongsTo(User::class, 'u_id');
    }

}
