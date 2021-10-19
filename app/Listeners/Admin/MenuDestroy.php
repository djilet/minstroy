<?php

namespace App\Listeners\Admin;

use App\Events\Admin\MenuDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MenuDestroy
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
     * @param  MenuDeleted  $event
     * @return void
     */
    public function handle(MenuDeleted $event)
    {
        $event->menu->pages()->delete();
    }
}
