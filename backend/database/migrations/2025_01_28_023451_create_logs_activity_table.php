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
        Schema::create('logs_activity', function (Blueprint $table) {
            $table->id();
            $table->string('model'); // Nome do modelo
            $table->unsignedBigInteger('model_id')->nullable(); // ID do registro no modelo
            $table->string('event'); // Tipo de evento: created, updated, deleted
            $table->json('data')->nullable(); // Dados registrados
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_activity');
    }
};
