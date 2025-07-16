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
        Schema::create('guide_report_expends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_report_id')->constrained('guide_reports')->onDelete('cascade')->comment('ガイド報告ID');
            $table->string('name')->comment('立替経費名');
            $table->bigInteger('price_including_tax')->comment('税込金額');
            $table->bigInteger('price_excluding_tax')->comment('税抜価格');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide_report_expends');
    }
};
