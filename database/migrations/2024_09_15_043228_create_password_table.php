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
        Schema::create('password', function (Blueprint $table) {
            $table->id();
            $table->string('pass');
            $table->unsignedBigInteger('game_id');

            $table->foreign('game_id')->references('id')->on('game')->onDelete('cascade');
            $table->timestamps();
        });
        Artisan::call('db:seed', ['--class' => 'PasswordSeeder']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password');
    }
};
