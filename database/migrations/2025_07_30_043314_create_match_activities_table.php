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
        Schema::create('match_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('restrict');
            $table->foreignId('player_id')->nullable()->constrained('players')->onDelete('restrict');
            $table->string('activity', 100)->comment('Jenis aktivitas: match_start, match_end, goal, own_goal, assist, foul, yellow_card, red_card, substitution, injury, penalty, penalty_missed, free_kick, corner, offside, handball, etc.');
            $table->time('time_activity')->comment('Waktu aktivitas dalam pertandingan (HH:MM:SS)');
            $table->text('detail')->nullable()->comment('Detail tambahan aktivitas (deskripsi, alasan, dll)');
            $table->timestamps();
            $table->softDeletes();
            
            // Index untuk optimasi query
            $table->index(['match_id', 'time_activity']);
            $table->index(['match_id', 'activity']);
            $table->index(['team_id', 'match_id']);
            $table->index(['player_id', 'match_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_activities');
    }
}; 