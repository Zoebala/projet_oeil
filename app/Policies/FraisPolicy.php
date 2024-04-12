<?php

namespace App\Policies;

use App\Models\Frais;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FraisPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return $user->hasPermissionTo("ViewAny Frais");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Frais $frais): bool
    {
        //
        return $user->hasPermissionTo("View Frais");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return $user->hasPermissionTo("Create Frais");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Frais $frais): bool
    {
        //
        return $user->hasPermissionTo("Update Frais");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Frais $frais): bool
    {
        //
        return $user->hasPermissionTo("Delete Frais");
    }
    public function deleteAny(User $user, Frais $frais): bool
    {
        //
        return $user->hasPermissionTo("DeleteAny Frais");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Frais $frais): bool
    {
        //
        return $user->hasRole("Admin");
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Frais $frais): bool
    {
        //
        return $user->hasRole("Admin");
    }
}
