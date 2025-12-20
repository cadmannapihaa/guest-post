<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorizationLog extends Model
{
    protected $fillable = [
        'user_id',
        'ability',
        'resource_type',
        'resource_id',
        'allowed',
        'reason',
    ];
}
