<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Owners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owners', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('notification_number')->nullable(true);
            $table->string('short_title')->nullable(true);
            $table->date('date_publication')->nullable(true);
            $table->date('date_expire')->nullable(true);
            $table->date('date_totals')->nullable(true);
            $table->string('title')->nullable(true);
            $table->string('phone')->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('address')->nullable(true);
            $table->string('contact_person')->nullable(true);
            $table->string('subject', 1000)->nullable(true);
            $table->string('supply_place')->nullable(true);
            $table->string('start_price')->nullable(true);
            $table->string('currency')->nullable(true);
            $table->date('date_consideration')->nullable(true);
            $table->string('consideration_place')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('owners');
    }
}
