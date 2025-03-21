<?php

namespace Innoboxrr\DomainManager\Http\Events\DomainRenewal\Listeners\ExportEvent;

use Innoboxrr\DomainManager\Notifications\DomainRenewal\ExportNotification;
use Innoboxrr\DomainManager\Http\Events\DomainRenewal\Events\ExportEvent;
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