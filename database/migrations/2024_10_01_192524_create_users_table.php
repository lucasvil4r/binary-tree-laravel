<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Nome do usuário
            $table->unsignedBigInteger('referrer_id')->nullable();  // Usuário que fez a indicação
            $table->unsignedBigInteger('left_child_id')->nullable();  // Filho à esquerda
            $table->unsignedBigInteger('right_child_id')->nullable(); // Filho à direita
            $table->unsignedBigInteger('points')->default(0);  // Pontos acumulados pelo usuário
            $table->timestamps();

            // Relacionamento com o próprio usuário (referrer/indicado)
            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('left_child_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('right_child_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
