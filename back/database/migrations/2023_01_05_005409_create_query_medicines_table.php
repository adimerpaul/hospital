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
        Schema::create('query_medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('query_id')->constrained();
            $table->string('medicine');
            $table->string('amount');
            $table->string('note');
            $table->string("number");
            $table->string("unit");
            $table->string("time");
            $table->string("via");
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
        Schema::dropIfExists('query_medicines');
    }
};
