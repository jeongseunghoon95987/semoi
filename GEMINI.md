C:\Users\jsh\PhpstormProjects\semoi\semoi 프로젝트에서 작업을 수행할 때는 '세모이 프로젝트 상세 기획안_.pdf' 파일을 항상 참조하여 개발 지침을 따르세요.


# Gemini 작업 가이드 (v1.2)

이 문서는 Gemini가 이 프로젝트에서 작업을 수행할 때 따라야 할 규칙과 지침을 정의합니다.
Gemini는 파일 수정, 생성, 삭제 등 모든 작업을 수행하기 전에 항상 이 문서의 내용을 먼저 확인하고 최대한 준수해야 합니다.

---

## 1. 코딩 스타일 (Coding Style)

- **기본 표준**: [PSR-12](https://www.php-fig.org/psr/psr-12/)를 기본 코딩 표준으로 합니다. (`phpcs.xml` 설정에 따름)
- **들여쓰기**: 스페이스 4칸을 사용합니다.
- **명명 규칙**:
  - **컨트롤러**: 단일 리소스는 파스칼 케이스. (예: `UserController`, `PharmdoraTvController`)
  - **모델**: 단일 리소스는 파스칼 케이스. (예: `User`, `PharmdoraTVLink`)
  - **메소드/함수**: 카멜 케이스. (예: `getPublishedPosts`, `storeVideo`)
  - **변수**: 카멜 케이스. (예: `$publishedPosts`, `$userData`)
  - **데이터베이스 테이블**: 스네이크 케이스, 복수형. (예: `users`, `pharmdora_tv_links`)
  - **Blade 파일**: 스네이크 케이스. (예: `create.blade.php`, `search_video.blade.php`)
  - **Route 이름**: 케밥 케이스 또는 스네이크 케이스. (예: `admin.pharmdora-tv.create`)
- **주석**:
  - 모든 공개(public) 메소드에는 PHPDoc 형식의 주석을 추가하는 것을 원칙으로 합니다.
  - 복잡한 비즈니스 로직에는 `//`를 사용하여 간결한 설명을 추가합니다.
- **PHP 일반**:
  - 모든 PHP 파일 상단에 `declare(strict_types=1);` 선언을 권장합니다.
  - 짧은 배열 문법 `[]`을 사용합니다. (긴 배열 문법 `array()` 사용 금지)
  - `dd()`나 `dump()`와 같은 디버깅 함수는 최종 커밋에 포함되지 않아야 합니다.
- **Blade 템플릿**:
  - `@` 지시어와 HTML 태그의 들여쓰기를 명확히 구분하여 가독성을 높입니다.
  - 복잡한 PHP 로직은 Blade 파일에 직접 작성하지 않고, 컨트롤러나 뷰 컴포저에서 처리하여 변수로 전달합니다.
  - `resources/views/` 하위의 Blade 파일을 수정하거나 새로 생성할 때에는, 해당 디렉토리 또는 유사한 기능을 하는 기존 파일들의 컨벤션(예: `x-layouts` 컴포넌트 사용 여부, HTML 구조, 클래스명 등)을 반드시 확인하고 준수해야 합니다.

## 2. 프레임워크 및 라이브러리 규칙 (Framework & Library Rules)

- **데이터베이스 쿼리**:
  - **Eloquent ORM** 사용을 원칙으로 합니다.
  - **N+1 문제 방지**: 관계를 로드할 때는 항상 `with()`를 사용한 **Eager Loading**을 적용해야 합니다. (예: `User::with('posts')->get()`)
  - 복잡한 조인이나 집계가 필요할 경우, Laravel의 **Query Builder** (`DB::table(...)`)를 사용합니다.
  - Raw SQL 쿼리 (`DB::raw()`)는 성능상 불가피한 경우를 제외하고는 사용을 지양합니다.
- **유효성 검사 (Validation)**:
  - 간단한 경우: 컨트롤러 메소드 내에서 `$request->validate([...])`를 사용합니다.
  - 복잡하거나 재사용이 필요한 경우: `php artisan make:request`를 통해 별도의 **Form Request 클래스**를 생성하여 사용합니다. **모든 CRUD 작업의 `store` 및 `update` 메소드에서는 Form Request 클래스 사용을 원칙으로 합니다.**
- **설정 (Configuration)**:
  - 모든 설정 값은 `config/` 디렉토리의 관련 파일에 정의합니다.
  - 민감한 정보(API 키 등)는 `.env` 파일에 정의하고, `config` 파일에서 `env('KEY_NAME', 'default_value')` 형식으로 호출합니다.
  - 애플리케이션 코드에서는 반드시 `config('filename.key')` 헬퍼를 사용하여 설정 값을 가져옵니다.
- **프론트엔드 (Frontend)**:
  - **JavaScript**: 현재 프로젝트는 jQuery를 광범위하게 사용하므로, 기존 파일 수정 시에는 jQuery를 사용하여 일관성을 유지합니다. 새로운 동적 UI를 만들 때는 Alpine.js 사용을 고려할 수 있습니다.
  - **CSS**: **Bootstrap 5**를 기본 CSS 프레임워크로 사용합니다. 모든 새로운 스타일링은 Bootstrap 5의 클래스와 컴포넌트를 사용하여 구현해야 합니다.

## 3. 커밋 메시지 규칙 (Commit Message Rules)

- **형식**: [Conventional Commits](https://www.conventionalcommits.org/) 명세를 따릅니다.
  - `type(scope): subject`
  - **본문(body)**에는 변경의 이유와 상세 내용을, **꼬리말(footer)**에는 관련 이슈 번호를 적습니다.
  - 예시:
    ```
    feat(pharmdora): 동적 링크 및 연락처 추가 기능 구현

    - create.blade.php에 관련 링크, 연락처, 담당자 정보를 동적으로 추가/삭제할 수 있는 UI를 추가함.
    - jQuery를 사용하여 행 추가/삭제 로직을 구현하고, 기존 스크립트와의 일관성을 유지함.

    Fixes: #456
    ```

## 4. 주요 아키텍처 (Key Architecture)

- 이 프로젝트는 전통적인 Laravel의 **MVC 패턴**을 따릅니다. 코드의 양이 많아지거나 로직이 복잡해질 경우, **Service-Repository 패턴** 도입을 적극적으로 고려합니다.
  - **Controller**: HTTP 요청을 받고 응답을 반환하는 역할만 수행합니다. 비즈니스 로직을 포함하지 않습니다.
  - **Service**: 여러 모델이나 Repository를 호출하거나 외부 API와 통신하는 등, 복잡한 비즈니스 로직을 처리합니다. 컨트롤러와 모델/레포지토리 사이의 중간 계층 역할을 합니다.
  - **Repository**: Eloquent 모델을 감싸 데이터베이스와의 상호작용(CRUD)만 담당합니다. Service 레이어에서 호출됩니다.
  - **Model**: 데이터베이스 테이블과의 상호작용 및 관계(Relationship) 정의를 담당합니다.
  - **Request**: 리퀘스트 클래스로 프론트엔드에서 백엔드로 넘어오는 데이터를 validate 한다.

## 5. 에러 핸들링 및 보안

- **에러 핸들링**: 모든 외부 API 호출 및 데이터베이스 트랜잭션과 같이 실패 가능성이 있는 로직은 `try-catch` 블록으로 감싸는 것을 원칙으로 합니다. 예외 발생 시 `Log` 파사드를 사용하여 에러를 기록합니다.
- **전역 예외 처리**: 특정 예외에 대한 전역적인 렌더링 또는 리포팅 로직은 `app/Exceptions/Handler.php` 파일에 정의합니다.
- **보안**: SQL 인젝션을 방지하기 위해 반드시 Eloquent나 Query Builder의 파라미터 바인딩을 사용합니다. 사용자 입력값은 항상 신뢰하지 않고, 적절한 유효성 검사와 이스케이프 처리를 수행합니다.

## 6. 클린 코드 원칙 (Clean Code Principles)

- **의미 있는 이름**: 변수, 함수, 클래스의 이름은 그 의도와 역할이 명확히 드러나도록 작성합니다. 축약어나 의미를 알 수 없는 이름(예: `$a`, `$data`, `proc()`)은 사용을 지양합니다.
- **작은 함수**: 함수는 **한 가지 기능**만 수행해야 하며, 작게 만들어야 합니다. 이상적으로는 20줄을 넘지 않도록 노력하고, 중첩(indentation) 레벨은 2단계를 초과하지 않도록 합니다.
- **부수 효과 제거 (No Side Effects)**: 함수는 명시된 동작만 수행해야 합니다. 함수 이름과 다른 숨겨진 기능을 만들지 않습니다. (예: `checkPassword()` 함수가 세션을 초기화해서는 안 됩니다.)
- **단일 책임 원칙 (Single Responsibility Principle, SRP)**: 하나의 클래스는 하나의 책임(변경의 이유)만 가져야 합니다. 예를 들어, `UserService`는 사용자 관련 비즈니스 로직만 처리하고, 이메일 발송이나 파일 처리 로직을 포함해서는 안 됩니다.
- **코드 중복 제거 (Don't Repeat Yourself, DRY)**: 동일한 코드 블록이 여러 곳에 반복된다면, 별도의 함수나 클래스 메소드로 분리하여 재사용합니다.

## 7. 문서화 규칙 (Documentation Rules)

프로젝트 문서는 `doc/` 디렉토리 내에 다음 규칙에 따라 저장되어야 합니다.

- **프로세스 및 워크플로우**: `doc/processes/` 디렉토리에 저장합니다. (예: `doc/processes/data_flow.md`)
- **데이터베이스 스키마**: `doc/schemas/` 디렉토리에 저장합니다. 각 테이블별로 별도의 파일을 생성하며, **스키마 변경 시 반드시 해당 파일을 업데이트해야 합니다.** (예: `doc/schemas/events.md`)
- **유비쿼터스 언어 및 핵심 개념**: `doc/ubiquitous_language/` 디렉토리에 저장합니다. (예: `doc/ubiquitous_language/core_concepts.md`)
- **크롤러 아키텍처**: `doc/crawler_architecture.md` 파일에 저장합니다.

**참고**: `doc/` 디렉토리 내의 모든 문서는 프로젝트의 핵심 지침이므로, 작업 전에 항상 참조해야 합니다.
