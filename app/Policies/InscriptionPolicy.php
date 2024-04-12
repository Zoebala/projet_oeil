<?php

namespace App\Policies;

use App\Models\Inscription;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InscriptionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return $user->hasPermissionTo("ViewAny Inscriptions");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Inscription $inscription): bool
    {
        //
        return $user->hasPermissionTo("View Inscriptions");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return $user->hasPermissionTo("Create Inscriptions");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Inscription $inscription): bool
    {
        //
        return $user->hasPermissionTo("Update Inscriptions");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Inscription $inscription): bool
    {
        //
        return $user->hasPermissionTo("Delete Inscriptions");
    }
    public function deleteAny(User $user): bool
    {
        //
        return $user->hasPermissionTo("DeleteAny Inscriptions");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Inscription $inscription): bool
    {
        //
        return $user->hasRole("Admin");
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Inscription $inscription): bool
    {
        //
        return $user->hasRole("Admin");
    }
}
