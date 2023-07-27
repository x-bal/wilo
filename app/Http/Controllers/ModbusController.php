<?php

namespace App\Http\Controllers;

use App\Models\Merge;
use App\Models\Modbus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModbusController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Modbus $modbus)
    {
        //
    }

    public function edit(Modbus $modbus)
    {
        //
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'field' => 'required',
            'val' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $modbus = Modbus::findOrFail(request('id'));

            if (request('field') == 'name') {
                $message = 'Modbus name successfully updated';
            }

            if (request('field') == 'satuan') {
                $message = 'Modbus satuan successfully updated';
            }

            if (request('field') == 'is_showed' && request('val') == 1) {
                $message = 'Modbus successfully showed';
            }

            if (request('field') == 'is_showed' && request('val') == 0) {
                $message = 'Modbus successfully not showed';
            }

            if (request('field') == 'is_showed' && request('val') == 1) {
                $count = Modbus::where('device_id', $modbus->device_id)->where('is_showed', 1)->count();
                if ($count >= 6) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Maximum show modbus is 6'
                    ], 200);
                } else {
                    $modbus->update([
                        request('field') => request('val')
                    ]);
                }
            } else {
                $modbus->update([
                    request('field') => request('val')
                ]);
            }

            DB::commit();



            return response()->json([
                'status' => 'success',
                'message' => $message
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function merge(Request $request)
    {
        $attr = $request->validate(
            [
                'name' => 'required',
                'convert' => 'required',
                'modbus_id' => 'required',
            ],
            [
                'modbus_id.required' => 'Select Modbus'
            ]
        );

        try {
            DB::beginTransaction();
            if (count($request->modbus_id) <= 2 && count($request->modbus_id) != 0) {
                $modbusOne = Modbus::findOrFail($request->modbus_id[0]);
                $modbusTwo = Modbus::findOrFail($request->modbus_id[1]);

                if ($modbusOne->merge == 0 && $modbusTwo->merge == 0) {
                    $decOne = dechex($modbusOne->val);
                    $decTwo = dechex($modbusTwo->val);

                    $val = $this->endian($request->convert, $decOne, $decTwo);

                    // dd($val);
                    $merge = Merge::create([
                        'device_id' => $modbusOne->device_id,
                        'name' => $request->name,
                        'type' => $request->convert,
                        'val' => $val
                    ]);

                    $modbusOne->update([
                        'merge_id' => $merge->id
                    ]);

                    $modbusTwo->update([
                        'merge_id' => $merge->id
                    ]);

                    History::create([
                        'device_id' => $merge->device_id,
                        'ket' => 'Merge ' . $modbusOne->name . ' & ' . $modbusTwo->name,
                        'val' => $val
                    ]);

                    DB::commit();

                    return back()->with('success', 'Modbus successfully merged');
                } else {
                    return back()->with('error', "Modbus has been merged");
                }
            } else {
                return back()->with('error', "Maximum merge is 2 modbus");
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
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

    public function deleteMerge(Merge $merge)
    {
        try {
            DB::beginTransaction();

            foreach ($merge->modbuses as $modbus) {
                $modbus->update(['merge_id' => 0]);
            }

            $merge->delete();

            DB::commit();
            return back()->with('success', 'Merge successfully deleted');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function change(Request $request)
    {
        try {
            DB::beginTransaction();
            $merge = Merge::find($request->id);

            $modbusOne = $merge->modbuses[0];
            $modbusTwo = $merge->modbuses[1];

            $decOne = dechex($modbusOne->val);
            $decTwo = dechex($modbusTwo->val);

            $result = $this->endian($request->type, $decOne, $decTwo);

            $merge->update([
                'val' => $result,
                'type' => $request->type
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Merge successfully changed',
                'val' => $result,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function math(Request $request)
    {
        try {
            DB::beginTransaction();
            $merge = Merge::find($request->id);

            $merge->update([
                'math' => '',
            ]);

            $merge->update([
                'math' => $request->math,
                'after' => $request->after,
            ]);


            History::create([
                'device_id' => $merge->device->id,
                'val' => $request->after,
                'ket' => 'Math ' . $merge->name
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Value Merge successfully updated',
                'merge' => $request->all()
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function updateMerge()
    {
        request()->validate([
            'id' => 'required',
            'field' => 'required',
            'val' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $merge = Merge::findOrFail(request('id'));
            $merge->update([
                request('field') => request('val')
            ]);

            DB::commit();

            if (request('field') == 'name') {
                $message = 'Merge name successfully updated';
            }

            if (request('field') == 'unit') {
                $message = 'Merge Unit successfully updated';
            }

            if (request('field') == 'is_used' && request('val') == 1) {
                $message = 'Merge successfully activated';
            }

            if (request('field') == 'is_used' && request('val') == 0) {
                $message = 'Merge successfully deactivated';
            }

            return response()->json([
                'status' => 'success',
                'message' => $message
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function find(Modbus $modbus)
    {
        if (request('from') != '' && request('to') != '') {
            $history = History::where('modbus_id', $modbus->id)->whereBetween('created_at', [request('from'), Carbon::parse(request('to'))->addDay(1)->format('Y-m-d')])->latest()->get();
        } else {
            $history = History::where('modbus_id', $modbus->id)->latest()->get();
        }

        return response()->json([
            'modbus' => $modbus,
            'history' => $history,
            'request' => request()->all(),
        ]);
    }
}
