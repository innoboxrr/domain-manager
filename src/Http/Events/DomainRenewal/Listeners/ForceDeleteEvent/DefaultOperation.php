<?php

namespace Innoboxrr\DomainManager\Http\Events\DomainRenewal\Listeners\ForceDeleteEvent;

use Innoboxrr\DomainManager\Http\Events\DomainRenewal\Events\ForceDeleteEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DefaultOperation
{
    
    public function __construct()
    {
        //
    }

    public function handle(ForceDeleteEvent $event)
    {
        //
    }

}
