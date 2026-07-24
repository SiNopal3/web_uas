<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Private channel for user-specific targeted events (Notifications, Account Status Changes)
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Public channel for global dashboard risk score, weather, exchange rates, and news updates
Broadcast::channel('dashboard.global', function () {
    return true;
});

// Private channel for admin-only console updates and real-time audit logs
Broadcast::channel('admin.console', function ($user) {
    return $user->isAdmin();
});

// Presence channel for real-time online/offline user presence tracking
Broadcast::channel('presence-online', function ($user) {
    if (auth()->check()) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => $user->isAdmin(),
        ];
    }
    return false;
});
