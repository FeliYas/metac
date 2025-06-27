<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->string('usuario')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('telefono')->unique()->nullable();
            $table->string('cuit')->unique()->nullable();
            $table->string('rol')->default('cliente')->nullable();
            $table->string('password')->nullable();
            $table->boolean('autorizado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
