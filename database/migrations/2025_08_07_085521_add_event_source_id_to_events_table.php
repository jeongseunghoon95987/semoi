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
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('event_source_id')->nullable()->constrained()->onDelete('set null');
            // nullable()을 사용하여 기존 데이터와의 호환성을 유지하고,
            // on_delete('set null')을 사용하여 EventSource가 삭제될 경우 해당 ID를 null로 설정합니다.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['event_source_id']);
            $table->dropColumn('event_source_id');
        });
    }
};