<?php

namespace App\Policies;

use App\User;
use App\Wallet;
use Illuminate\Auth\Access\HandlesAuthorization;

class WalletPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any wallets.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the wallet.
     *
     * @param User $user
     * @param Wallet $wallet
     * @return mixed
     */
    public function view(User $user, Wallet $wallet)
    {
        return $wallet->hasAccess($user);
    }

    /**
     * Determine whether the user can create wallets.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    public function adjustment(User $user, Wallet $wallet)
    {
        return $user->id == $wallet->user_id;
    }

    // @codeCoverageIgnoreStart
    /**
     * Determine whether the user can update the wallet.
     *
     * @param User $user
     * @param Wallet $wallet
     * @return mixed
     */
    public function update(User $user, Wallet $wallet)
    {
        //
    }

    /**
     * Determine whether the user can delete the wallet.
     *
     * @param User $user
     * @param Wallet $wallet
     * @return mixed
     */
    public function delete(User $user, Wallet $wallet)
    {
        //
    }

    /**
     * Determine whether the user can restore the wallet.
     *
     * @param User $user
     * @param Wallet $wallet
     * @return mixed
     */
    public function restore(User $user, Wallet $wallet)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the wallet.
     *
     * @param User $user
     * @param Wallet $wallet
     * @return mixed
     */
    public function forceDelete(User $user, Wallet $wallet)
    {
        //
    }
    // @codeCoverageIgnoreEnd
}
