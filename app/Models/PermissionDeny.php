<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionDeny extends Model
{
    protected $fillable = [
        'user_id',
        'role_name',
        'permission',
        'resource_type',
        'resource_id',
        'reason',
        'expires_at',
    ];

    protected $dates = ['expires_at'];
}
