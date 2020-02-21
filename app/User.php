<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function allWallets()
    {
        $ownedWallets = $this->wallets()->get();
        $sharedWallets = $this->sharedWallets()->get();

        return $ownedWallets->merge($sharedWallets);
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function sharedWallets()
    {
        return $this->belongsToMany(Wallet::class, 'wallet_access');
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
