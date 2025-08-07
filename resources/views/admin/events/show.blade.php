<x-layouts.app :title="__('Event Details')">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">이벤트 상세</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">ID</dt>
                        <dd class="col-sm-9">{{ $event->id }}</dd>

                        <dt class="col-sm-3">이름</dt>
                        <dd class="col-sm-9">{{ $event->name }}</dd>

                        <dt class="col-sm-3">설명</dt>
                        <dd class="col-sm-9">{{ $event->description }}</dd>

                        <dt class="col-sm-3">시작일</dt>
                        <dd class="col-sm-9">{{ $event->start_date->format('Y-m-d H:i') }}</dd>

                        <dt class="col-sm-3">종료일</dt>
                        <dd class="col-sm-9">{{ $event->end_date->format('Y-m-d H:i') }}</dd>

                        <dt class="col-sm-3">URL</dt>
                        <dd class="col-sm-9"><a href="{{ $event->url }}" target="_blank">{{ $event->url }}</a></dd>

                        <dt class="col-sm-3">썸네일</dt>
                        <dd class="col-sm-9">
                            @if ($event->thumbnail_url)
                                <img src="{{ $event->thumbnail_url }}" alt="{{ $event->name }}" class="img-fluid" width="200">
                            @else
                                N/A
                            @endif
                        </dd>

                        <dt class="col-sm-3">소스</dt> {{-- 추가 --}}
                        <dd class="col-sm-9">{{ $event->eventSource ? $event->eventSource->name : 'N/A' }}</dd> {{-- 추가 --}}

                        <dt class="col-sm-3">생성일</dt>
                        <dd class="col-sm-9">{{ $event->created_at->format('Y-m-d H:i') }}</dd>

                        <dt class="col-sm-3">수정일</dt>
                        <dd class="col-sm-9">{{ $event->updated_at->format('Y-m-d H:i') }}</dd>
                    </dl>
                    <hr>
                    <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-warning">수정</a>
                    <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('정말 삭제하시겠습니까?')">삭제</button>
                    </form>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">목록으로</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>