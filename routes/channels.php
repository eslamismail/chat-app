<?php

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
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('post', function ($user) {
    return $user;
});
Broadcast::channel('post.{id}', function ($user, $id) {
    return $user;
});

Broadcast::channel('postsUpdated', function ($user) {
    return $user;
});

// Broadcast::channel('likePost', function ($user) {
//     \Log::info('USER_BROADCAST', [$user]);
//     return $user;
// });
