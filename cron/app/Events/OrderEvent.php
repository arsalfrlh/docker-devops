<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $order;

    /**
     * Create a new event instance.
     */
    public function __construct($order)
    {
        $this->order = $order;

        Log::info('OrderEvent::__construct', [
            'order_id' => $order?->id,
            'order_type' => is_object($order) ? get_class($order) : gettype($order),
            'order' => $order,
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        Log::info('OrderEvent::broadcastOn', [
            'order_id' => $this->order?->id,
            'channel' => 'order-event',
        ]);

        return [
            new Channel('order-event'),
        ];
    }

    public function broadcastAs(): string
    {
        Log::info('OrderEvent::broadcastAs', [
            'order_id' => $this->order?->id,
            'event_name' => 'orderUpdate',
        ]);

        return 'orderUpdate';
    }

    public function broadcastWith(): array
    {
        Log::info('OrderEvent::broadcastWith START', [
            'order_id' => $this->order?->id,
            'order_type' => is_object($this->order)
                ? get_class($this->order)
                : gettype($this->order),
        ]);

        $payload = [
            'order' => $this->order,
        ];

        Log::info('OrderEvent::broadcastWith SUCCESS', [
            'order_id' => $this->order?->id,
            'payload' => $payload,
        ]);

        return $payload;
    }
}