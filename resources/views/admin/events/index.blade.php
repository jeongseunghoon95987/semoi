<x-layouts.app :title="__('Events Management')">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">이벤트 목록</h5>
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary">새 이벤트 추가</a>
          </div>
        </div>
        <div class="card-body">
          @if (session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif

          <table class="table table-bordered">
            <thead>
            <tr>
              <th>ID</th>
              <th>이름</th>
              <th>설명</th>
              <th>시작일</th>
              <th>종료일</th>
              <th>URL</th>
              <th>썸네일</th>
              <th>소스</th> {{-- 추가 --}}
              <th>생성일</th>
              <th>작업</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($events as $event)
              <tr>
                <td>{{ $event->id }}</td>
                <td>{{ $event->name }}</td>
                <td>{{ Str::limit($event->description, 50) }}</td>
                <td>{{ $event->start_date->format('Y-m-d H:i') }}</td>
                <td>{{ $event->end_date->format('Y-m-d H:i') }}</td>
                <td><a href="{{ $event->url }}" target="_blank">{{ $event->url }}</a></td>
                <td>
                  @if ($event->thumbnail_url)
                    <img src="{{ $event->thumbnail_url }}" alt="{{ $event->name }}" width="50">
                  @else
                    N/A
                  @endif
                </td>
                <td>{{ $event->eventSource ? $event->eventSource->name : 'N/A' }}</td> {{-- 추가 --}}
                <td>{{ $event->created_at->format('Y-m-d H:i') }}</td>
                <td>
                  <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm btn-warning">수정</a>
                  <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('정말 삭제하시겠습니까?')">삭제
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
          <div class="mt-5">
            {{ $events->links("vendor.pagination.bootstrap-5") }} {{-- 페이지네이션 링크 --}}
          </div>
        </div>
      </div>
    </div>
  </div>
</x-layouts.app>
