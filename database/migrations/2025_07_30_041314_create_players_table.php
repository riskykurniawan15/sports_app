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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->unsignedTinyInteger('squad_number');
            $table->unsignedTinyInteger('height')->comment('Height in cm');
            $table->unsignedTinyInteger('weight')->comment('Weight in kg');
            $table->foreignId('position_id')->constrained('positions')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['team_id', 'squad_number'], 'players_team_squad_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
