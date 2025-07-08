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
        Schema::table('prices', function (Blueprint $table) {
            $table->string('duration', 32)->comment('サービス利用時間')->change();
            $table->integer('price_excluding_tax')->nullable()->comment('税抜価格')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->string('duration', 32)->comment(null)->change();
            $table->integer('price_excluding_tax')->nullable()->comment(null)->change();
        });
    }
};
