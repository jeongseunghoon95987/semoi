# `event_sources` Table Schema

`event_sources` 테이블은 크롤러가 정보를 수집할 대상 웹사이트(소스)와 해당 사이트의 구조를 분석하기 위한 규칙(CSS 선택자)을 저장합니다.

- **테이블명**: `event_sources`

| 컬럼명 | 데이터 타입 | 설명 |
|---|---|---|
| `id` | `bigint` (unsigned) | 고유 식별자 (Primary Key) |
| `name` | `varchar(255)` | 관리자가 식별하기 위한 소스의 이름 |
| `url` | `varchar(255)` | 크롤링 대상 페이지의 실제 주소 |
| `description` | `text` (nullable) | 소스에 대한 간단한 설명 |
| `is_active` | `boolean` | 현재 이 소스에서 정보를 수집할지 여부 (기본값: true) |
| `list_selector` | `varchar(255)` | 이벤트 목록 전체를 감싸는 CSS 선택자 |
| `item_selector` | `varchar(255)` | 목록 내 개별 이벤트를 감싸는 CSS 선택자 |
| `title_selector` | `varchar(255)` | 이벤트 제목에 해당하는 CSS 선택자 |
| `date_selector` | `varchar(255)` (nullable) | 이벤트 날짜에 해당하는 CSS 선택자 |
| `url_selector` | `varchar(255)` | 이벤트 상세 페이지 링크에 해당하는 CSS 선택자 |
| `description_selector` | `varchar(255)` (nullable) | 이벤트 설명에 해당하는 CSS 선택자 |
| `created_at` | `timestamp` | 레코드 생성 시각 |
| `updated_at` | `timestamp` | 레코드 마지막 수정 시각 |
