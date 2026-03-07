<?php

namespace App\Policies;

use App\Models\Lpj;
use App\Models\User;

class LpjPolicy
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

    public function view(User $user, Lpj $lpj): bool
    {
        return (int) $lpj->created_by === (int) $user->id;
    }

    public function create(User $user): bool
    {
        // Approved middleware already protects entry; allow create by default
        return true;
    }

    public function update(User $user, Lpj $lpj): bool
    {
        return (int) $lpj->created_by === (int) $user->id;
    }

    public function delete(User $user, Lpj $lpj): bool
    {
        return (int) $lpj->created_by === (int) $user->id;
    }

    // Document-related abilities
    public function download(User $user, Lpj $lpj): bool
    {
        return $this->view($user, $lpj);
    }

    public function preview(User $user, Lpj $lpj): bool
    {
        return $this->view($user, $lpj);
    }

    public function regenerate(User $user, Lpj $lpj): bool
    {
        return $this->update($user, $lpj);
    }
}

