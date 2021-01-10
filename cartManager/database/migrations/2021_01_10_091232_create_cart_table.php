<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartTable extends Migration
{

    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->string('session_id');
            $table->string('item');
            $table->integer('amount');
        });
    }


    public function down()
    {
        Schema::dropIfExists('cart');
    }
}
