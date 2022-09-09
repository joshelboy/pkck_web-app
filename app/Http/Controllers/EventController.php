<?php

namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all()
        ->sortByDesc('time')
        ->sortByDesc('date')
        ->toArray();
        return array_reverse($events);      
    }
    public function store(Request $request)
    {
        $event = new Event([
            'title' => $request->input('title'),
            'time' => $request->input('time'),
            'date' => $request->input('date'),
            'location' => $request->input('location'),
            'userCreated' => $request->input('userCreated')
        ]);
        $event->save();
        return response()->json($event->id);
    }
    public function show($id)
    {
        $event = Event::find($id);
        return response()->json($event);
    }
    public function update($id, Request $request)
    {
        $event = Event::find($id);
        $event->update($request->all());
        return response()->json('Event updated!');
    }
    public function destroy($id)
    {
        $event = Event::find($id);
        $event->delete();
        return response()->json('Event deleted!');
    }
}
