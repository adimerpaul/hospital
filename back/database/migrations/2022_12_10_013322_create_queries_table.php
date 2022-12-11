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
        Schema::create('queries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->double('weight',11,2)->nullable();
            $table->double('height',11,2)->nullable();
            $table->string('bloodType')->nullable();
            $table->string('allergies')->nullable();
            $table->string('TA')->nullable();
            $table->string('FC')->nullable();
            $table->string('FR')->nullable();

            $table->string('pregnant')->nullable();
            $table->string('FUM')->nullable();
            $table->string('SDG')->nullable();
            $table->string('FU')->nullable();

            $table->string('smoker')->nullable();
            $table->string('smokerDescription')->nullable();
            $table->string('alcohol')->nullable();
            $table->string('alcoholDescription')->nullable();

            $table->string('note')->nullable();
            $table->string('exploration')->nullable();

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
        Schema::dropIfExists('queries');
    }
};
