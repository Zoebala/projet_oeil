<?php

namespace App\Policies;

use App\Models\Departement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DepartementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return $user->hasPermissionTo('ViewAny Departements');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Departement $departement): bool
    {
        //
        return $user->hasPermissionTo('View Departements');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return $user->hasPermissionTo('Create Departements');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Departement $departement): bool
    {
        //
        return $user->hasPermissionTo('Update Departements');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Departement $departement): bool
    {
        //
        return $user->hasPermissionTo('Delete Departements');
    }
    public function deleteAny(User $user): bool
    {
        //
        return $user->hasPermissionTo('DeleteAny Departements');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Departement $departement): bool
    {
        //
        return $user->hasRole("Admin");
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Departement $departement): bool
    {
        //
        return $user->hasRole("Admin");
    }
}
