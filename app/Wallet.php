<?php

namespace App;

use App\Traits\HasTransactions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Wallet extends Model
{
    use HasTransactions;

    protected $guarded = [
        'balance',
    ];

    protected $attributes = [
        'currency' => Currency::USD,
    ];

    protected $appends = [
        'balance',
    ];

    public function getBalanceAttribute()
    {
        return $this->balance();
    }

    public static function open($data)
    {
        return DB::transaction(function () use ($data) {
            return tap(
                self::create(Arr::except($data, 'initial_balance')),
                function (self $wallet) use ($data) {
                    $wallet->deposit([
                        'note' => 'initial balance',
                        'amount' => Arr::get($data, 'initial_balance', 0),
                        'user_id' => $data['user_id'],
                    ]);
                }
            );
        });
    }

    public function share(User $user)
    {
        return WalletAccess::updateOrCreate([
            'wallet_id' => $this->id,
            'user_id' => $user->id,
        ]);
    }

    public function unshare(User $user)
    {
        return WalletAccess::where([
            'wallet_id' => $this->id,
            'user_id' => $user->id,
        ])->delete();
    }

    public function hasAccess(User $user)
    {
        return $this->own($user) || $this->sharedWith($user);
    }

    public function own(User $user)
    {
        return $this->user_id == $user->id;
    }

    public function sharedWith(User $user)
    {
        return WalletAccess::where([
            'wallet_id' => $this->id,
            'user_id' => $user->id,
        ])->exists();
    }

    public function adjustBalance($new_balance)
    {
        $adjustment = $this->balance() - $new_balance;

        if($adjustment > 0){
            $this->withdraw([
                'amount' => $adjustment,
                'note' => 'adjustment transaction'
            ]);
        }elseif($adjustment < 0){
            $this->deposit([
                'amount' => abs($adjustment),
                'note' => 'adjustment transaction'
            ]);
        }

        return $this;
    }
}
