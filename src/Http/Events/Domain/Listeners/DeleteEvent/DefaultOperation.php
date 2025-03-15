<?php

namespace Innoboxrr\DomainManager\Http\Events\Domain\Listeners\DeleteEvent;

use Innoboxrr\DomainManager\Http\Events\Domain\Events\DeleteEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DefaultOperation
{
    
    public function __construct()
    {
        //
    }

    public function handle(DeleteEvent $event)
    {
        //
    }

}
