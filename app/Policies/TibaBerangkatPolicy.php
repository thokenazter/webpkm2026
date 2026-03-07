<?php

namespace App\Policies;

use App\Models\TibaBerangkat;
use App\Models\User;

class TibaBerangkatPolicy
{
    /**
     * Grant all abilities to super admin/admin email.
     */
    public function before(User $user, string $ability)
    {
        if ($user->isSuperAdmin() || $user->email === 'admin@admin.com') {
            return true;
        }
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TibaBerangkat $tb): bool
    {
        return (int) $tb->created_by === (int) $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, TibaBerangkat $tb): bool
    {
        return (int) $tb->created_by === (int) $user->id;
    }

    public function delete(User $user, TibaBerangkat $tb): bool
    {
        return (int) $tb->created_by === (int) $user->id;
    }

    public function download(User $user, TibaBerangkat $tb): bool
    {
        return $this->view($user, $tb);
    }

    public function quickUpdate(User $user, TibaBerangkat $tb): bool
    {
        return $this->update($user, $tb);
    }
}

