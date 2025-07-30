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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->string('venue', 200)->comment('Nama stadion/lokasi pertandingan');
            $table->dateTime('match_datetime')->comment('Waktu pertandingan');
            $table->foreignId('home_team_id')->constrained('teams')->onDelete('restrict');
            $table->foreignId('away_team_id')->constrained('teams')->onDelete('restrict');
            $table->json('match_metadata')->nullable()->comment('JSON untuk skor, pemenang, dll');
            $table->timestamps();
            $table->softDeletes();
            
            // Index untuk optimasi query
            $table->index(['match_datetime', 'home_team_id']);
            $table->index(['match_datetime', 'away_team_id']);
            $table->index('match_datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
}; 