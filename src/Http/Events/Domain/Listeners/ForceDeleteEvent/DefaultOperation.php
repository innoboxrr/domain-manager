<?php

namespace Innoboxrr\DomainManager\Http\Events\Domain\Listeners\ForceDeleteEvent;

use Innoboxrr\DomainManager\Http\Events\Domain\Events\ForceDeleteEvent;
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
