<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('full_name');
      $table->string('cedula')->unique();
      $table->string('cargo')->nullable();
      $table->string('oficina')->nullable();

      // Para reconocimiento facial: guarda el embedding como JSON (lista de floats)
      $table->json('embedding')->nullable();

      // Opcional: ruta/foto base si quieres
      $table->string('photo_path')->nullable();

      $table->timestamps();
    });
  }

  public function down(): void {
    Schema::dropIfExists('users');
  }
};
