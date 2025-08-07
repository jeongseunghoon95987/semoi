@section('title', __('Event Sources'))
<x-layouts.app :title="__('Event Sources')">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">이벤트 소스 목록</h5>
                        <a href="{{ route('admin.event-sources.create') }}" class="btn btn-primary">새 소스 추가</a>
                        <form action="{{ route('admin.event-sources.crawl') }}" method="POST" class="d-inline ms-2">
                            @csrf
                            <button type="submit" class="btn btn-success">수동 크롤링 시작</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>이름</th>
                                <th>URL</th>
                                <th>활성 상태</th>
                                <th>마지막 크롤링</th>
                                <th>생성일</th>
                                <th>작업</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eventSources as $source)
                                <tr>
                                    <td>{{ $source->id }}</td>
                                    <td>{{ $source->name }}</td>
                                    <td><a href="{{ $source->url }}" target="_blank">{{ $source->url }}</a></td>
                                    <td>{{ $source->is_active ? '활성' : '비활성' }}</td>
                                    <td>{{ $source->last_crawled_at ? $source->last_crawled_at->format('Y-m-d H:i') : '없음' }}</td>
                                    <td>{{ $source->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('admin.event-sources.edit', $source) }}" class="btn btn-sm btn-warning">수정</a>
                                        <form action="{{ route('admin.event-sources.crawl') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $source->id }}">
                                            <button type="submit" class="btn btn-sm btn-info">개별 크롤링</button>
                                        </form>
                                        <form action="{{ route('admin.event-sources.destroy', $source) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">삭제</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>