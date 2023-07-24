<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserAuditLog;

class UserAuditObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        UserAuditLog::create([
            'user_id' => $user->id,
            'action' => 'created',
            'old_values' => null,
            'new_values' => $user->toJson(),
            'user_agent' => $user->name,
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $changes = $user->only(['name', 'email']);
        $oldValues = json_encode([
            'name' => $user->getOriginal('name'),
            'email' => $user->getOriginal('email'),
        ]);
        $newValues = json_encode($changes);

        UserAuditLog::create([
            'user_id' => $user->id,
            'action' => 'updated',
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'user_agent' => $user->name,
        ]);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        UserAuditLog::create([
            'user_id' => $user->id,
            'action' => 'deleted',
            'old_values' => null,
            'new_values' => null,
            'user_agent' => $user->name,
        ]);
    }
}
