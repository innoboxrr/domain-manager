<?php

namespace Innoboxrr\DomainManager\Http\Events\DomainContact\Listeners\ExportEvent;

use Innoboxrr\DomainManager\Notifications\DomainContact\ExportNotification;
use Innoboxrr\DomainManager\Http\Events\DomainContact\Events\ExportEvent;
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