<?php

namespace App\Listeners;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        $user = User::findOrFail($event->user->id);
        activity()
            ->causedBy($user)
            ->inLog('logout')
            ->event('logout')
            ->withProperties(['login_at' => Carbon::now()])
            ->log($user->name . ' just logged out of the system');
    }
}
