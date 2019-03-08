<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tenders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenders', function (Blueprint $table) {
            //$table->bigIncrements('id');
            $table->increments('id');
            $table->string('name');
            $table->date('date_publication');
            $table->date('date_expire');
            $table->date('date_totals');
            $table->string('link_details');
            $table->unsignedInteger('owner_id');
        });

        Schema::table('tenders', function($table) {
            $table->foreign('owner_id')
                ->references('id')
                ->on('owners')
            ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenders');
    }
}
