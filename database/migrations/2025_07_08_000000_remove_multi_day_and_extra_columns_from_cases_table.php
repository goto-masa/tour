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
        Schema::table('cases', function (Blueprint $table) {
            $table->dropColumn([
                'multi_day',
                'day2_start',
                'day2_end',
                'day3_start',
                'day3_end',
                'extra_requests',
                'others_schedule',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->boolean('multi_day')->default(false)->comment('複数日で依頼する');
            $table->timestamp('day2_start')->nullable()->comment('2日目のサービス手配日時');
            $table->timestamp('day2_end')->nullable()->comment('2日目のサービス終了日時');
            $table->timestamp('day3_start')->nullable()->comment('3日目のサービス手配日時');
            $table->timestamp('day3_end')->nullable()->comment('3日目のサービス終了日時');
            $table->text('extra_requests')->nullable()->comment('その他お申し付け事項');
            $table->text('others_schedule')->nullable()->comment('4日目以降のサービス手配日時、終了日時');
        });
    }
}; 