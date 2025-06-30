<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guide_report', function (Blueprint $table) {
            $table->id();
            $table->string('guide_email')->comment('メールアドレス');
            $table->string('guide_name')->comment('Your Name');
            $table->string('guest_name')->comment('Guest Name');
            $table->unsignedTinyInteger('number_of_guests')->comment('Number of Guests');
            $table->date('service_date')->comment('Guide Start Time');
            $table->date('finish_time')->comment('Guide Finish Time');
            $table->unsignedSmallInteger('duration')->nullable()->comment('Duration');
            $table->json('schedules')->nullable()->comment('Guide Schedule');
            $table->json('expenses')->nullable()->comment('Replacement Expenses');
            $table->text('report')->nullable()->comment('Guide Report');
            $table->text('comment')->nullable()->comment('備考');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guide_report');
    }
}; 