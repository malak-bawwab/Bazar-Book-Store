<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
 Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bookId');
            $table->string('customerName');
            $table->string('date');
            $table->string('title');



        });

          
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
             Schema::dropIfExists('orders');
   //
    }
}
