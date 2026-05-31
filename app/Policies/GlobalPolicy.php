<?php

namespace App\Policies;

use App\Models\User;

class GlobalPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, $model)
    {
        return true;
    }

    public function create(User $user)
    {
        return !$user->hasRole('pengawas');
    }

    public function update(User $user, $model)
    {
        return !$user->hasRole('pengawas');
    }

    public function delete(User $user, $model)
    {
        return !$user->hasRole('pengawas');
    }

    public function deleteAny(User $user)
    {
        return !$user->hasRole('pengawas');
    }

    public function restore(User $user, $model)
    {
        return !$user->hasRole('pengawas');
    }

    public function restoreAny(User $user)
    {
        return !$user->hasRole('pengawas');
    }

    public function forceDelete(User $user, $model)
    {
        return !$user->hasRole('pengawas');
    }

    public function forceDeleteAny(User $user)
    {
        return !$user->hasRole('pengawas');
    }
}
