<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServerController extends Controller
{
    function index()
    {
        $title = 'Server Setting';
        $breadcrumbs = ['Server Setting'];
        $server = Server::first();

        return view('server.index', compact('title', 'breadcrumbs', 'server'));
    }

    function update(Request $request, Server $server)
    {

        try {
            DB::beginTransaction();

            $server->update([
                'host' => $request->host,
                'port' => $request->port,
                'username' => $request->username,
                'password' => $request->password,
                'client_id' => $request->client_id,
            ]);

            DB::commit();

            return back()->with('success', "Server successfully updated");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
