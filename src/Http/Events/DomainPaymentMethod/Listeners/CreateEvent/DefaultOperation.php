<?php

namespace Innoboxrr\DomainManager\Http\Events\DomainPaymentMethod\Listeners\CreateEvent;

use Innoboxrr\DomainManager\Http\Events\DomainPaymentMethod\Events\CreateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DefaultOperation
{
    
    public function __construct()
    {
        //
    }

    public function handle(CreateEvent $event)
    {
        //
    }

}
