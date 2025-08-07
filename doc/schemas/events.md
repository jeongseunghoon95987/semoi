# `events` Table Schema

`events` 테이블은 크롤링을 통해 수집된 개별 이벤트의 상세 정보를 저장합니다.

- **테이블명**: `events`

| 컬럼명 | 데이터 타입 | 설명 |
|---|---|---|
| `id` | `bigint` (unsigned) | 고유 식별자 (Primary Key) |
| `name` | `varchar(255)` | 이벤트의 공식 명칭 |
| `description` | `text` | 이벤트에 대한 상세 설명 |
| `start_date` | `datetime` | 이벤트 시작 일시 |
| `end_date` | `datetime` | 이벤트 종료 일시 |
| `url` | `varchar(255)` | 이벤트의 원본 상세 페이지 URL |
| `thumbnail_url` | `varchar(255)` (nullable) | 이벤트 썸네일 이미지 URL |
| `created_at` | `timestamp` | 레코드 생성 시각 |
| `updated_at` | `timestamp` | 레코드 마지막 수정 시각 |