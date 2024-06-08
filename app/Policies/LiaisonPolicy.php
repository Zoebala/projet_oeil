<?php

namespace App\Policies;

use App\Models\Liaison;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LiaisonPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo("ViewAny Liaisons");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Liaison $liaison): bool
    {
        //
        return $user->hasPermissionTo("View Liaisons");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return $user->hasPermissionTo("Create Liaisons");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Liaison $liaison): bool
    {
        //
        return $user->hasPermissionTo("Update Liaisons");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Liaison $liaison): bool
    {
        //
        return $user->hasPermissionTo("Delete Liaisons");
    }
    public function deleteAny(User $user): bool
    {
        //
        return $user->hasPermissionTo("DeleteAny Liaisons");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Liaison $liaison): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Liaison $liaison): bool
    {
        //
    }
}
