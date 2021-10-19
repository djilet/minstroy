<?php

namespace App\Listeners\Admin;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;

class LastLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $event->user->timestamps = false;
        $event->user->update([
            'login_at' => Carbon::now(),
            'login_ip' => request()->ip()
        ]);
    }
}
