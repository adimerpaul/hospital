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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->string('barra')->default('')->nullable();
            $table->string('name');
            $table->integer('stock');
            $table->integer('stockMin');
            $table->integer('stockMax');
            $table->string('photo');
            $table->string('color');
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
        Schema::dropIfExists('products');
    }
};
