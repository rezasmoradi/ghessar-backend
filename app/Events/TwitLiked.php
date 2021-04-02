<?php

namespace App\Events;

use App\Models\Twit;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TwitLiked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Twit
     */
    private $twit;

    /**
     * Create a new event instance.
     *
     * @param Twit $twit
     */
    public function __construct(Twit $twit)
    {
        $this->twit = $twit;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('twit-liked');
    }

    public function broadcastAs()
    {
        return 'twit-liked';
    }
}
