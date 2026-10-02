<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

/**
 * Every permission check (route `can:` middleware, sidebar filtering) relies on
 * Spatie's model_has_roles, not the legacy `users.role` column. Any code path
 * that writes the column without calling assignRole() leaves the account
 * half-broken (menu items missing, pages 403). Self-heal on login. Additive
 * only — never removes extra roles assigned via Super > Role & Permission.
 */
class EnsureSpatieRoleMatchesColumn
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        if (! $user instanceof User || ! $user->role || $user->hasRole($user->role)) {
            return;
        }

        if (! Role::where('name', $user->role)->where('guard_name', 'web')->exists()) {
            return;
        }

        $user->assignRole($user->role);
        Log::info('Self-healed missing Spatie role on login', ['user_id' => $user->id, 'role' => $user->role]);
    }
}
