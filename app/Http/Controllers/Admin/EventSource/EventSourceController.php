<?php

namespace app\Http\Controllers\Admin\EventSource;

use App\Http\Controllers\Controller;
use app\Http\Requests\Admin\EventSource\StoreEventSourceRequest;
use app\Http\Requests\Admin\EventSource\UpdateEventSourceRequest;
use App\Models\EventSource;
use Illuminate\Http\Request;

class EventSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eventSources = EventSource::all();
        return view('admin.event_sources.index', compact('eventSources'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.event_sources.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventSourceRequest $request)
    {
        $validatedData = $request->validated();

        EventSource::create($validatedData);

        return redirect()->route('admin.event-sources.index')->with('success', '이벤트 소스가 성공적으로 추가되었습니다.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EventSource $eventSource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventSource $eventSource)
    {
        return view('admin.event_sources.edit', compact('eventSource'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventSourceRequest $request, EventSource $eventSource)
    {
        $validatedData = $request->validated();

        $eventSource->update($validatedData);

        return redirect()->route('admin.event-sources.index')->with('success', '이벤트 소스가 성공적으로 업데이트되었습니다.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventSource $eventSource)
    {
        $eventSource->delete();

        return redirect()->route('admin.event-sources.index')->with('success', '이벤트 소스가 성공적으로 삭제되었습니다.');
    }

    public function crawl(Request $request)
    {
        $eventSourceId = $request->input('id');

        // 이벤트 디스패치
        event(new \App\Events\StartCrawling($eventSourceId));

        if ($eventSourceId) {
            return redirect()->route('admin.event-sources.index')->with('success', "이벤트 소스 ID: {$eventSourceId} 크롤링 작업이 큐에 추가되었습니다.");
        } else {
            return redirect()->route('admin.event-sources.index')->with('success', '전체 이벤트 소스 크롤링 작업이 큐에 추가되었습니다.');
        }
    }

    public function indexForCrawler(Request $request)
    {
        $query = EventSource::where('is_active', true);

        if ($request->has('id')) {
            $query->where('id', $request->input('id'));
        }

        $eventSources = $query->get();

        return response()->json($eventSources);
    }
}
