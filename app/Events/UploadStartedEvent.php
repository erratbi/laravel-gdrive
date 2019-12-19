<?php

namespace App\Events;

use App\Url;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UploadStartedEvent implements ShouldBroadcastNow {
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Url
     */
    protected $url;


    /**
     * Create a new event instance.
     *
     * @param Url $url
     */
    public function __construct(Url $url) {
        $this->url = $url;
        $this->url->update(['status' => 'UPLOADING']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn() {
        return [new Channel('urls')];
    }

    /**
     * @return string
     */
    public function broadcastAs() {
        return 'upload.started';
    }

    public function broadcastWith() {
        return ['url' => $this->url];
    }
}
