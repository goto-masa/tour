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
        Schema::create('cases', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->string('hotel_name')->comment('ホテル名');
            $table->string('writer_name')->comment('記入者名');
            $table->string('guest_name')->comment('ゲスト名（代表者名）');
            $table->unsignedSmallInteger('guest_count')->comment('ゲスト人数');
            $table->text('request_detail')->comment('ご依頼内容（サービスの内容）');
            $table->string('dispatch_location')->comment('ガイドを派遣する場所');
            $table->timestamp('service_start')->nullable()->comment('サービス手配日時');
            $table->timestamp('service_end')->nullable()->comment('サービス終了日時');
            $table->unsignedInteger('service_hours')->comment('サービス提供時間');
            $table->string('guide_language', 50)->comment('ガイド言語');
            $table->string('vehicle_type', 100)->comment('希望車種');
            $table->text('desired_areas')->comment('観光エリア、スポット、アクティビティ');
            $table->timestamp('deleted_at')->nullable()->comment('削除日');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
