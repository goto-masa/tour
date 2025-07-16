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

        Schema::create('case_travel_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_case_id')->constrained('hotel_cases')->onDelete('cascade')->comment('案件ID');
            $table->string('name')->comment('プログラム名');
            $table->string('guest_name')->nullable()->comment('ゲスト名');
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
        Schema::dropIfExists('case_travel_programs');

        Schema::rename('hotel_cases', 'cases');

    }
};
