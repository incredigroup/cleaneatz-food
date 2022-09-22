<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogins extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'ip_address', 'created_at'];

    protected $dates = ['created_at'];
}
