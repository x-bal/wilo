<?php

namespace App\Listeners;

use App\Models\Server;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\Message;

class MqttSubscribeListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $mqttSettings = Server::where('status', 1)->first();

        config([
            'mqtt.host' => $mqttSettings->host,
            'mqtt.port' => $mqttSettings->port,
            'mqtt.username' => $mqttSettings->username,
            'mqtt.password' => $mqttSettings->password,
        ]);

        MQTT::subscribe('your/mqtt/topic', function (Message $message) {
            // Process incoming MQTT message
            $payload = $message->getContent();
            return $payload;
        });
    }
}
