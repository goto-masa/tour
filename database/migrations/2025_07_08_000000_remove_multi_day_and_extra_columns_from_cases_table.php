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
            $columns = [
                'multi_day',
                'day2_start',
                'day2_end',
                'day3_start',
                'day3_end',
                'extra_requests',
                'others_schedule',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('cases', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            if (!Schema::hasColumn('cases', 'multi_day')) {
                $table->boolean('multi_day')->default(false)->comment('複数日で依頼する');
            }
            if (!Schema::hasColumn('cases', 'day2_start')) {
                $table->timestamp('day2_start')->nullable()->comment('2日目のサービス手配日時');
            }
            if (!Schema::hasColumn('cases', 'day2_end')) {
                $table->timestamp('day2_end')->nullable()->comment('2日目のサービス終了日時');
            }
            if (!Schema::hasColumn('cases', 'day3_start')) {
                $table->timestamp('day3_start')->nullable()->comment('3日目のサービス手配日時');
            }
            if (!Schema::hasColumn('cases', 'day3_end')) {
                $table->timestamp('day3_end')->nullable()->comment('3日目のサービス終了日時');
            }
            if (!Schema::hasColumn('cases', 'extra_requests')) {
                $table->text('extra_requests')->nullable()->comment('その他お申し付け事項');
            }
            if (!Schema::hasColumn('cases', 'others_schedule')) {
                $table->text('others_schedule')->nullable()->comment('4日目以降のサービス手配日時、終了日時');
            }
        });
    }
};
