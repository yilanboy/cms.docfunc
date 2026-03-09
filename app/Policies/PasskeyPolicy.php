<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Passkey;

class PasskeyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Admin $admin, Passkey $passkey): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $admin, Passkey $passkey): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin, Passkey $passkey): bool
    {
        return $passkey->owner_id === $admin->id && $passkey->owner_type === Admin::class;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $admin, Passkey $passkey): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Admin $admin, Passkey $passkey): bool
    {
        return false;
    }
}
