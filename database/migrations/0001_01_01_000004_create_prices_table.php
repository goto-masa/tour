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
        Schema::create('prices', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->string('name')->comment('ホテル名');
            $table->string('service', 255)->unique()->comment('対応サービス');
            $table->string('type', 255)->nullable()->comment('車種');
            $table->unsignedBigInteger('duration')->comment('サービス利用時間');
            $table->softDeletes()->comment('削除日');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
