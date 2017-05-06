<?php
namespace Components\Vids;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class VidServiceProvider extends EventServiceProvider
{
    protected $listen = [
        'Components\Vids\Events\VidsDisplayWasRequested' => [
            'Components\Vids\Listeners\AddVids'
        ],
    ];
}
