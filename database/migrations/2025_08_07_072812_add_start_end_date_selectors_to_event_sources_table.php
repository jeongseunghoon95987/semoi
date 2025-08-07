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
        Schema::table('event_sources', function (Blueprint $table) {
            $table->string('start_date_selector')->nullable()->after('date_selector');
            $table->string('end_date_selector')->nullable()->after('start_date_selector');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_sources', function (Blueprint $table) {
            $table->dropColumn(['start_date_selector', 'end_date_selector']);
        });
    }
};