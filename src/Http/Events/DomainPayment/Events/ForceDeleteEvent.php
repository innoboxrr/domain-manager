<?php

namespace Innoboxrr\DomainManager\Http\Events\DomainPayment\Events;

use Innoboxrr\DomainManager\Models\DomainPayment;
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

    public $domainPayment;
    public $data;
    public $response;
    public $locale;

    public function __construct(DomainPayment $domainPayment, array $data, $response, $locale = 'en')
    {
        $this->domainPayment = $domainPayment;
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