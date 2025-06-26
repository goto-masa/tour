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
            $table->string('name', 255)->comment('ユーザ名');
            $table->string('email', 255)->unique()->comment('メールアドレス（ログインID）');
            $table->timestamp('email_verified_at')->nullable()->comment('メール認証日時');
            $table->string('password', 255)->comment('パスワード');
            $table->rememberToken()->comment('ログイン保持トークン');
            $table->timestamp('last_login_at')->nullable()->comment('最終ログイン日時');
            $table->softDeletes()->comment('削除日');
            $table->timestamps();
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
