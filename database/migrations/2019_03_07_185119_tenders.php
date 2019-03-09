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
            $table->string('name', 2000)->nullable(true);
            $table->date('date_publication')->nullable(true);
            $table->date('date_expire')->nullable(true);
            $table->date('date_totals')->nullable(true);
            $table->string('link_details')->nullable(true);
            $table->unsignedInteger('owner_id')->nullable(true);
        });

//        Schema::table('tenders', function($table) {
//            $table->foreign('owner_id')
//                ->references('id')
//                ->on('owners')
//            ;
//        });
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
