<?php

namespace App\Policies;

use App\Models\Annee;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AnneePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
       return $user->hasPermissionTo('ViewAny Annees');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Annee $annee): bool
    {
        //
        return $user->hasPermissionTo('View Annee');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return $user->hasPermissionTo('Create Annees');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Annee $annee): bool
    {
        //
        return $user->hasPermissionTo('Update Annees');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Annee $annee): bool
    {
        //
        return $user->hasPermissionto('Delete Annees');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Annee $annee): bool
    {
        //
        return $user->hasRole("Admin");
    }
    public function DeleteAny(User $user): bool
    {
        //
        return $user->hasRole("Admin");
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Annee $annee): bool
    {
        //
        return $user->hasRole("Admin");
    }
}
