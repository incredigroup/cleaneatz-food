<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOptIn extends Model
{
    protected $fillable = ['email', 'first_name', 'last_name', 'store_location_id', 'exported_at'];
}
