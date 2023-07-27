<?php

namespace App\Http\Controllers;

use App\Models\DigitalInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DigitalInputController extends Controller
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

    public function show(DigitalInput $digitalInput)
    {
        //
    }

    public function edit(DigitalInput $digitalInput)
    {
        //
    }

    public function update(Request $request)
    {
        request()->validate([
            'id' => 'required',
            'field' => 'required',
            'val' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $digital = DigitalInput::findOrFail(request('id'));
            $digital->update([
                request('field') => request('val')
            ]);

            DB::commit();

            if (request('field') == 'name') {
                $message = 'Digital input name successfully updated';
            }

            if (request('field') == 'yes') {
                $message = 'Digital input alias (yes) successfully updated';
            }

            if (request('field') == 'no') {
                $message = 'Digital input alias (no) successfully updated';
            }

            if (request('field') == 'is_used' && request('val') == 1) {
                $message = 'Digital Input successfully activated';
            }

            if (request('field') == 'is_used' && request('val') == 0) {
                $message = 'Digital Input successfully deactivated';
            }

            return response()->json([
                'status' => 'success',
                'message' => $message
            ]);
        } catch (\Throwable $th) {
            DB::commit();
            return response()->json([
                'status' => 'failed',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function destroy(DigitalInput $digitalInput)
    {
        //
    }
}
