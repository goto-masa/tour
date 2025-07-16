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
        Schema::table('guide_reports', function (Blueprint $table) {
            //
            $table->bigInteger('expend_price')->comment('立替経費金額(合計)')->after('report');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guide_reports', function (Blueprint $table) {
            //
            $table->dropColumn('expend_price');
        });
    }
};
