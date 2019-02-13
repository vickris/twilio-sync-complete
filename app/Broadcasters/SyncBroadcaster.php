<?php

namespace App\Broadcasters;

use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Twilio\Rest\Sync\V1\ServiceContext;
use Illuminate\Support\Arr;

class SyncBroadcaster extends Broadcaster {

    /**
     * @var ServiceContext
     */
    protected $sync;

    /**
     * Create a new broadcaster instance.
     *
     * @param ServiceContext $sync
     * @return void
     */
    public function __construct( ServiceContext $sync ) {
        $this->sync = $sync;
    }

    /**
     * Broadcast the given event.
     *
     * @param  array  $channels
     * @param  string  $event
     * @param  array  $payload
     * @return void
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        $socket = Arr::pull($payload, 'socket');
        foreach($this->formatChannels($channels) as $channel) {
            try {
                $response = $this->sync
                    ->syncStreams($channel)
                    ->streamMessages
                    ->create([
                        'type' => $event,
                        'payload' => $payload,
                        'identity' => $socket,
                    ]);
                if ($response instanceof StreamMessageInstance) {
                    continue;
                }
            } catch (TwilioException $e) {
                if ($e->getCode() === self::TWILIO_EXCEPTION_NOT_FOUND) {
                    // Skip this broadcast because no listeners are available to receive the message
                    continue;
                }
                throw new BroadcastException('Failed to broadcast to Sync: ' . $e->getMessage());
            }
        }
    }

    /**
     * Authenticate the incoming request for a given channel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function auth($request)
    {
        //
    }

    /**
     * Return the valid authentication response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $result
     * @return mixed
     */
    public function validAuthenticationResponse($request, $result)
    {
        //
    }
}
