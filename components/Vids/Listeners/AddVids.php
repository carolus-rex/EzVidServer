<?php

namespace Components\Vids\Listeners;

use Components\Vids\Events\VidsDisplayWasRequested;
use Components\Vids\Jobs\AddVids as AddVidsJob;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\DB;

class AddVids
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
     * @param  VidsDisplayWasRequested  $event
     * @return void
     */
    public function handle(VidsDisplayWasRequested $event)
    {
        // Remember '\\\\' = '\'
        if (!(DB::table("jobs")->where('payload', 'like', '%Components\\\\\\\\Vids\\\\\\\\Jobs\\\\\\\\AddVids%')->first()))
            dispatch(new AddVidsJob());
    }
}
