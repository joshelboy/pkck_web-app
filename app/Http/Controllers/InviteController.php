<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invite;

class InviteController extends Controller
{
    public function index()
    {
        $invites = Invite::all()->toArray();
        return array_reverse($invites);      
    }
    public function store(Request $request)
    {
        $data = $request->all();

        foreach ($data as $key) {
            $invite = new Invite([
                'userID' => $key['oauth_id'],
                'eventID' => $key['eventID'],
                'status' => 'created'
            ]);
            $invite->save();    
        }

        return response()->json('Invite created!');
    }
    public function show($id)
    {
        $invite = Invite::find($id);
        return response()->json($invite);
    }
    public function update($id, Request $request)
    {
        $invite = Invite::find($id);
        $invite->update($request->all());
        return response()->json('Invite updated!');
    }
    public function destroy($id)
    {
        $invite = Invite::find($id);
        $invite->delete();
        return response()->json('Invite deleted!');
    }
}
