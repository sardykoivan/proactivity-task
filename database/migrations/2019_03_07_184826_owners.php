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
            $table->integer('notification_number');
            $table->string('short_title');
            $table->date('date_publication');
            $table->date('date_expire');
            $table->date('date_totals');
            $table->string('title');
            $table->string('phone');
            $table->string('email');
            $table->string('contact_person');
            $table->string('subject');
            $table->string('supply_place');
            $table->string('start_price');
            $table->string('currency');
            $table->date('date_consideration');
            $table->string('consideration_place');
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
