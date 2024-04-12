<?php

namespace App\Policies;

use App\Models\Paiement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaiementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return $user->hasPermissionTo("ViewAny Paiements");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Paiement $paiement): bool
    {
        //
        return $user->hasPermissionTo("View Paiements");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return $user->hasPermissionTo("Create Paiements");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Paiement $paiement): bool
    {
        //
        return $user->hasPermissionTo("Update Paiements");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Paiement $paiement): bool
    {
        //
        return $user->hasPermissionTo("Delete Paiements");
    }
    public function deleteAny(User $user): bool
    {
        //
        return $user->hasPermissionTo("DeleteAny Paiements");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Paiement $paiement): bool
    {
        //
        return $user->hasRole("Admin");
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Paiement $paiement): bool
    {
        //
        return $user->hasRole("Admin");
    }
}
