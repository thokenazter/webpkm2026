<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    /**
     * Super admin/admin email gets all abilities.
     */
    public function before(User $user, string $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * View any employee saldo listing page.
     */
    public function viewSaldoAny(User $user): bool
    {
        return true;
    }

    /**
     * View a specific employee saldo details (must be part of user's LPJ).
     */
    public function viewSaldo(User $user, Employee $employee): bool
    {
        // Always allow a user to view their own saldo if linked
        if (!is_null($user->employee_id) && (int)$user->employee_id === (int)$employee->id) {
            return true;
        }

        // Otherwise, allow if the employee appears in an LPJ created by the user
        $byDocument = $employee->lpjParticipants()
            ->whereHas('lpj', fn($q) => $q->where('created_by', $user->id))
            ->exists();
        if ($byDocument) return true;
        return $employee->creditedLpjParticipants()
            ->whereHas('lpj', fn($q) => $q->where('created_by', $user->id))
            ->exists();
    }
}
