<?php

namespace Innoboxrr\DomainManager\Http\Events\DomainTld\Listeners\ExportEvent;

use Innoboxrr\DomainManager\Notifications\DomainTld\ExportNotification;
use Innoboxrr\DomainManager\Http\Events\DomainTld\Events\ExportEvent;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendExportNotification
{

    public function __construct()
    {
        //
    }

    public function handle(ExportEvent $event)
    {
        $event->user->notify((new ExportNotification($event->data))->locale($event->locale));
    }

}