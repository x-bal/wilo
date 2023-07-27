<?php

namespace Database\Seeders;

use App\Models\Server;
use Illuminate\Database\Seeder;

class ServerMqttSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Server::create([
            'host' => 'broker.emqx.io',
            'port' => '1883',
            'username' => '',
            'password' => '',
            'client_id' => 'mqttx_4d7abbe78',
            'status' => 1
        ]);
    }
}
