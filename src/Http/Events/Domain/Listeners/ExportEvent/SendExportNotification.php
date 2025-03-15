<?php

namespace Innoboxrr\DomainManager\Http\Events\Domain\Listeners\ExportEvent;

use Innoboxrr\DomainManager\Notifications\Domain\ExportNotification;
use Innoboxrr\DomainManager\Http\Events\Domain\Events\ExportEvent;
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