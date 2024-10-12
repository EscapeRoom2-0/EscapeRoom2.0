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
        Schema::create('temporizador', function (Blueprint $table) {
            $table->id();
            $table->string('tempT');
            $table->string('tempJ1');
            $table->string('tempJ2');
            $table->timestamps();
        });
        Artisan::call('db:seed', ['--class' => 'TemporizadorSeeder']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporizador');
    }
};
