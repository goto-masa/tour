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
        Schema::create('admins', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->string('name', 255)->unique()->comment('ユーザ名');
            $table->string('password', 255)->comment('パスワード');
            $table->timestamp('deleted_at')->nullable()->comment('削除日');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->timestamp('created_at')->useCurrent()->comment('作成日時');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
