<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            // 크롤링 규칙을 위한 CSS 선택자
            $table->string('list_selector')->comment('이벤트 목록을 감싸는 선택자');
            $table->string('item_selector')->comment('개별 이벤트를 감싸는 선택자');
            $table->string('title_selector')->comment('이벤트 제목 선택자');
            $table->string('date_selector')->nullable()->comment('이벤트 날짜 선택자');
            $table->string('url_selector')->comment('상세 페이지 링크 선택자');
            $table->string('description_selector')->nullable()->comment('이벤트 설명 선택자');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_sources');
    }
};
