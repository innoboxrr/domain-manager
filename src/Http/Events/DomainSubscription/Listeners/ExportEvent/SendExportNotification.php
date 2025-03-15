<?php

namespace Innoboxrr\DomainManager\Http\Events\DomainSubscription\Listeners\ExportEvent;

use Innoboxrr\DomainManager\Notifications\DomainSubscription\ExportNotification;
use Innoboxrr\DomainManager\Http\Events\DomainSubscription\Events\ExportEvent;
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