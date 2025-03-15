<?php

namespace Innoboxrr\DomainManager\Http\Events\DomainTld\Events;

use Innoboxrr\DomainManager\Models\DomainTld;
use Illuminate\Support\Facades\App;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ForceDeleteEvent
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $domainTld;
    public $data;
    public $response;
    public $locale;

    public function __construct(DomainTld $domainTld, array $data, $response, $locale = 'en')
    {
        $this->domainTld = $domainTld;
        $this->data = $data;
        $this->response = $response;
        $this->locale = $locale;
        App::setLocale($this->locale);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

}