<?php

namespace App\Models;

use App\Traits\HasStateOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasStateOptions, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'address',
        'address',
        'address2',
        'city',
        'state',
        'zip',
        'phone',
        'billing_first_name',
        'billing_last_name',
        'billing_company',
        'billing_address',
        'billing_address2',
        'billing_city',
        'billing_state',
        'billing_zip',
        'billing_email',
        'billing_phone',
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function storeLocation()
    {
        return $this->belongsTo(StoreLocation::class);
    }

    public function mealPlanCart()
    {
        return $this->hasMany(MealPlanCart::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    function isAdmin()
    {
        return $this->role === 'admin';
    }

    function isStore()
    {
        return $this->role === 'store';
    }

    function isCustomer()
    {
        return $this->role === 'user';
    }

    public function scopeByUsername($query, $username)
    {
        return $query->where('users.username', '=', $username);
    }

    public function getTimezone()
    {
        return 'America/New_York';
    }
}
