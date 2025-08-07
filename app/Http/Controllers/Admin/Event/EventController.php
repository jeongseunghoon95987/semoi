<?php

namespace App\Http\Controllers\Admin\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Http\Requests\Admin\Event\StoreEventRequest; // 네임스페이스 변경
use App\Http\Requests\Admin\Event\UpdateEventRequest; // 네임스페이스 변경
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::orderBy('created_at', 'desc')->paginate(10); // 생성일 내림차순, 10개씩 페이지네이션
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        Event::create($request->validated());
        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function storeFromCrawler(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'url' => 'required|url|max:255',
            'thumbnail_url' => 'nullable|url|max:255',
            'event_source_id' => 'required|exists:event_sources,id', // 추가
        ]);

        Event::create($validatedData);

        return response()->json(['message' => 'Event created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $event->update($request->validated());
        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }
}
