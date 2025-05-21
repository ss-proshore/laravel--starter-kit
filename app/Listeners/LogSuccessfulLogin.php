<?php

namespace App\Listeners;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogin
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
    public function handle(Login $event): void
    {   
        $user = User::findOrFail($event->user->id);
        activity()
            ->causedBy($user)
            ->inLog('login')
            ->event('login')
            ->withProperties(['login_at' => Carbon::now()])
            ->log($user->name . ' just logged in at the system');
    }
}
