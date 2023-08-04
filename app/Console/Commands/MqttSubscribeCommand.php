<?php

namespace App\Console\Commands;

use App\Models\Device;
use App\Models\History;
use App\Models\Server;
use App\Models\Temporary;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Salman\Mqtt\MqttClass\Mqtt;

class MqttSubscribeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:subs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to MQTT topics';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $server = Server::where('status', 1)->first();

        config(['mqtt.host' => $server->host]);
        config(['mqtt.username' => $server->username]);
        config(['mqtt.password' => $server->password]);
        config(['mqtt.port' => $server->port]);

        $mqtt = new Mqtt();

        $topics = Device::pluck('topic');

        foreach ($topics as $topic) {
            $mqtt->ConnectAndSubscribe($topic, function ($topic, $message) {
                $device = Device::where('topic', $topic)->first();

                if ($device && $device->is_active == 1) {
                    $json = json_decode($message, true);

                    $valmodbus = array_slice($json['data'], 0, 16);
                    $valDigital = array_slice($json['data'], 16, 23);
                    $address = $json['address'];
                    $idmodbus = $json['idm'];
                    $modbusUsed = array_slice($json['used'], 0, 16);
                    $digitalUsed = array_slice($json['used'], 0, 16);

                    $this->insertModbus($device, $valmodbus, $address, $idmodbus, $modbusUsed);
                    $this->insertDigital($device, $valDigital, $digitalUsed);
                }
            });
        }

        $mqtt->loop(true);
    }

    function insertModbus($device, $valmodbus, $address, $idmodbus, $modbusUsed)
    {
        try {
            DB::beginTransaction();

            foreach ($device->modbuses as $key => $mod) {
                Temporary::updateOrCreate(
                    [
                        'device_id' => $device->id,
                        'modbus_id' => $mod->id
                    ],
                    [
                        'val' => $valmodbus[$key],
                        'ket' => "Insert Data " . $mod->name,
                        'is_used' => $modbusUsed[$key]
                    ]
                );
            }


            $temporaries = Temporary::where('device_id', $device->id)->where('status', 1)->where('modbus_id', '!=', 0)->get();

            foreach ($device->modbuses as $i => $modbus) {
                if ($modbus->math != NULL) {
                    $math = explode(',', $modbus->math);

                    if ($math[0] == 'x') {
                        $after = $valmodbus[$i] * floatval($math[1]);
                    }

                    if ($math[0] == ':') {
                        $after = $valmodbus[$i] / floatval($math[1]);
                    }

                    if ($math[0] == '+') {
                        $after = $valmodbus[$i] + floatval($math[1]);
                    }

                    if ($math[0] == '-') {
                        $after = $valmodbus[$i] - floatval($math[1]);
                    }

                    if ($math[0] == '&') {
                        $rumus = explode('&', $math[1]);
                        $after = ((($valmodbus[$i] / floatval($rumus[2])) - 4) / 16) * (floatval($rumus[0]) - floatval($rumus[1])) + floatval($rumus[1]);
                    }
                }

                $modbus->update([
                    'address' => $address[$i],
                    'id_modbus' => $idmodbus[$i],
                    'val' => $valmodbus[$i],
                    'is_used' => $modbusUsed[$i],
                    'math' => $modbus->after == NULL ? 'x,1' : $modbus->math,
                    'after' => $after,
                ]);

                History::create([
                    'device_id' => $device->id,
                    'modbus_id' => $modbus->id,
                    'ket' => 'Insert Data ' . $modbus->name,
                    'val' => $after,
                    'time' => date('Y-m-d H:i')
                ]);
            }

            foreach ($temporaries as $tmp) {
                $tmp->update([
                    'status' => 0
                ]);
            }

            foreach ($device->merges as $i => $merge) {
                $modbuses[$i] = [];
                foreach ($merge->modbuses as $mod) {
                    array_push($modbuses[$i], $mod->val);
                }

                $valMerge = $this->endian($merge->type, dechex($modbuses[$i][0]), dechex($modbuses[$i][1]));

                if ($merge->math != NULL) {
                    $mathMerge = explode(',', $merge->math);

                    if ($mathMerge[0] == 'x') {
                        $afterMerge = $valMerge * floatval($mathMerge[1]);
                    }

                    if ($mathMerge[0] == ':') {
                        $afterMerge = $valMerge / floatval($mathMerge[1]);
                    }

                    if ($mathMerge[0] == '+') {
                        $afterMerge = $valMerge + floatval($mathMerge[1]);
                    }

                    if ($mathMerge[0] == '-') {
                        $afterMerge = $valMerge - floatval($mathMerge[1]);
                    }

                    if ($mathMerge[0] == '&') {
                        $rumusMerge = explode('&', $mathMerge[1]);
                        $afterMerge = ((($valMerge / floatval($rumusMerge[2])) - 4) / 16) * (floatval($rumusMerge[0]) - floatval($rumusMerge[1])) + floatval($rumusMerge[1]);
                    }
                }

                $merge->update([
                    'val' => $valMerge,
                    'math' => $merge->after == NULL ? 'x,1' : $merge->math,
                    'after' => $merge->after == NULL || $merge->after == 0 ? $valMerge * 1 : $afterMerge
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th->getMessage();
        }
    }

    function insertDigital($device, $valDigital, $digitalUsed)
    {
        try {
            DB::beginTransaction();

            foreach ($device->digitalInputs as $i => $digital) {
                Temporary::updateOrCreate(
                    [
                        'device_id' => $device->id,
                        'digital_input_id' => $digital->id,
                    ],
                    [
                        'val' => $valDigital[$i],
                        'ket' => 'Insert Data ' . $digital->name,
                        'is_used' => $digitalUsed[$i],
                        'status' => 1
                    ]
                );
            }

            foreach ($device->digitalInputs as $i => $digital) {
                $digital->update([
                    'is_used' => $digitalUsed[$i],
                    'val' => $valDigital[$i],
                ]);

                History::create([
                    'device_id' => $device->id,
                    'digital_input_id' => $digital->id,
                    'ket' => 'Insert Data ' . $digital->name,
                    'val' => $valDigital[$i],
                    'time' => date('Y-m-d H:i')
                ]);
            }

            DB::commit();

            // return response()->json([
            //     'status' => 'success',
            // ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th->getMessage();
        }
    }

    public function hex2float($strHex)
    {
        $hex = sscanf($strHex, "%02x%02x%02x%02x%02x%02x%02x%02x");
        $bin = implode('', array_map('chr', $hex));
        $array = unpack("Gnum", $bin);
        return $array['num'];
    }

    public function endian($convert, $decOne, $decTwo)
    {
        $lengthOne = strlen($decOne);
        $diffOne = 4 - $lengthOne;
        $lengthTwo = strlen($decTwo);
        $diffTwo = 4 - $lengthTwo;
        $addOne = '';
        $addTwo = '';


        if ($diffOne > 0) {
            for ($i = 1; $i < $diffOne; $i++) {
                $addOne .= 0;
            }
        }

        if ($diffTwo > 0) {
            for ($i = 1; $i < $diffTwo; $i++) {
                $addTwo .= 0;
            }
        }

        $decOne = $addOne . $decOne;
        $decTwo = $addTwo . $decTwo;

        $hexOne = str_split($decOne);
        $hexTwo = str_split($decTwo);

        $a = $hexOne[0] . $hexOne[1];
        $b = $hexOne[2] . $hexOne[3];
        $c = $hexTwo[0] . $hexTwo[1];
        $d = $hexTwo[2] . $hexTwo[3];

        if ($convert == 'be') {
            $hexa = $a . $b . $c . $d;

            $hexConvert = $this->hex2float($hexa);
        }

        if ($convert == 'le') {
            $hexa = $d . $c . $b . $a;

            $hexConvert = $this->hex2float($hexa);
        }

        if ($convert == 'mbe') {
            $hexa = $b . $a . $d . $c;

            $hexConvert = $this->hex2float($hexa);
        }

        if ($convert == 'mle') {
            $hexa = $c . $d . $a . $b;

            $hexConvert = $this->hex2float($hexa);
        }

        return $hexConvert;
    }
}
