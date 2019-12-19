<?php

namespace App\Events;

use App\Url;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DownloadErrorEvent implements ShouldBroadcastNow {
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Url
     */
    protected $url;
    /**
     * @var \Exception | null
     */
    protected $error;

    /**
     * Create a new event instance.
     *
     * @param Url $url
     * @param \Exception|null $e
     */
    public function __construct(Url $url, \Exception $e = null) {
        $this->url = $url;
        $this->error = $e;
        $this->url->update(['status' => 'ERROR']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn() {
        return new Channel('urls');
    }

    public function broadcastAs() {
        return 'download.error';
    }

    public function broadcastWith() {
        return ['url' => $this->url, 'error' => $this->error];
    }
}
