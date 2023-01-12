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
            $table->foreignId('query_id')->constrained()->onDelete('cascade');
            $table->string('medicine')->nullable();
            $table->string('amount')->nullable();
            $table->string('note')->nullable();
            $table->string("number")->nullable();
            $table->string("unit")->nullable();
            $table->string("time")->nullable();
            $table->string("via")->nullable();
            $table->string("diagnosis")->nullable();
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
