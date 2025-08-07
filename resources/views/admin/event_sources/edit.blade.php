@section('title', __('Edit Event Source'))
<x-layouts.app :title="__('Edit Event Source')">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">이벤트 소스 수정</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('event-sources.update', $eventSource) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">이름</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $eventSource->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="url" class="form-label">URL</label>
                            <input type="url" class="form-control" id="url" name="url" value="{{ old('url', $eventSource->url) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">설명</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $eventSource->description) }}</textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $eventSource->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">활성 상태</label>
                        </div>

                        <hr>
                        <h6>크롤링 규칙 (CSS 선택자)</h6>
                        <div class="mb-3">
                            <label for="list_selector" class="form-label">이벤트 목록 선택자</label>
                            <input type="text" class="form-control" id="list_selector" name="list_selector" value="{{ old('list_selector', $eventSource->list_selector) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="item_selector" class="form-label">개별 이벤트 선택자</label>
                            <input type="text" class="form-control" id="item_selector" name="item_selector" value="{{ old('item_selector', $eventSource->item_selector) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="title_selector" class="form-label">제목 선택자</label>
                            <input type="text" class="form-control" id="title_selector" name="title_selector" value="{{ old('title_selector', $eventSource->title_selector) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_selector" class="form-label">날짜 선택자 (단일)</label>
                            <input type="text" class="form-control" id="date_selector" name="date_selector" value="{{ old('date_selector', $eventSource->date_selector) }}">
                            <div class="form-text">예시: `8.18 (월) ~ 12.15 (월)`, `2025.08.07`, `2025년 8월 7일`</div>
                        </div>
                        <div class="mb-3">
                            <label for="start_date_selector" class="form-label">시작일 선택자</label>
                            <input type="text" class="form-control" id="start_date_selector" name="start_date_selector" value="{{ old('start_date_selector', $eventSource->start_date_selector) }}">
                            <div class="form-text">예시: `2025-08-07`, `8.18`</div>
                        </div>
                        <div class="mb-3">
                            <label for="end_date_selector" class="form-label">종료일 선택자</label>
                            <input type="text" class="form-control" id="end_date_selector" name="end_date_selector" value="{{ old('end_date_selector', $eventSource->end_date_selector) }}">
                            <div class="form-text">예시: `2025-08-09`, `12.15`</div>
                        </div>
                        <div class="mb-3">
                            <label for="url_selector" class="form-label">URL 선택자</label>
                            <input type="text" class="form-control" id="url_selector" name="url_selector" value="{{ old('url_selector', $eventSource->url_selector) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description_selector" class="form-label">설명 선택자</label>
                            <input type="text" class="form-control" id="description_selector" name="description_selector" value="{{ old('description_selector', $eventSource->description_selector) }}">
                        </div>
                        <div class="mb-3">
                            <label for="thumbnail_selector" class="form-label">썸네일 선택자</label>
                            <input type="text" class="form-control" id="thumbnail_selector" name="thumbnail_selector" value="{{ old('thumbnail_selector', $eventSource->thumbnail_selector) }}">
                        </div>

                        <button type="submit" class="btn btn-primary">수정</button>
                        <a href="{{ route('event-sources.index') }}" class="btn btn-secondary">목록으로</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
