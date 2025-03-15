<?php

namespace Innoboxrr\DomainManager\Http\Events\DomainDns\Listeners\UpdateEvent;

use Innoboxrr\DomainManager\Http\Events\DomainDns\Events\UpdateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DefaultOperation
{
    
    public function __construct()
    {
        //
    }

    public function handle(UpdateEvent $event)
    {
        //
    }

}
