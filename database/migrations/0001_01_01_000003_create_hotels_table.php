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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->string('name')->comment('ホテル名');
            $table->string('address')->comment('住所');
            $table->unsignedBigInteger('tel')->comment('電話番号');
            $table->unsignedBigInteger('contact')->comment('担当連絡先');
            $table->string('lang')->comment('言語');
            $table->string('note')->nullable()->comment('備考');
            $table->timestamp('deleted_at')->nullable()->comment('削除日');
            $table->timestamp('created_at')->nullable()->comment('作成日時');
            $table->timestamp('updated_at')->nullable()->comment('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
