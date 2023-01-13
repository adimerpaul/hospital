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
            $table->string('sat')->nullable();
            $table->string('tem')->nullable();
            $table->string('ta')->nullable();
            $table->string('fc')->nullable();
            $table->string('fr')->nullable();
            $table->string('blood')->nullable();

            $table->string('pregnant')->nullable();
            $table->date('FUM')->nullable();
            $table->string('SDG')->nullable();
            $table->string('FU')->nullable();

            $table->string('smoker')->nullable();
            $table->string('smokerDescription')->nullable();
            $table->string('alcohol')->nullable();
            $table->string('alcoholDescription')->nullable();

            $table->string('note1')->nullable();
            $table->string('note2')->nullable();
            $table->string('note3')->nullable();
            $table->string('note4')->nullable();

            $table->string('exploration')->nullable();

//            $table->string('prescription1')->nullable();
//            $table->string('prescription2')->nullable();
//            $table->string('prescription3')->nullable();
//            $table->string('prescription4')->nullable();
//            $table->string('prescription5')->nullable();

            $table->text('observations')->nullable();

            $table->string('summary')->nullable();
            $table->string('action')->nullable();
            $table->string('tomografias')->nullable();
            $table->string('ecografias')->nullable();
            $table->string('laboratorios')->nullable();

            $table->date('date');
            $table->time('time');

            $table->foreignId('user_id')->constrained();
            $table->foreignId('hospital_id')->constrained();
//            $table->foreignId('sale_id')->constrained()->nullable();
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
