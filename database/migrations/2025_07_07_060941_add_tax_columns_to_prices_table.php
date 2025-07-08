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
            $table->integer('price_excluding_tax')->comment('税抜価格')->after('duration');
            $table->integer('price_including_tax')->comment('税込価格')->after('price_excluding_tax');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->dropColumn(['price_excluding_tax', 'price_including_tax']);
        });
    }
};
