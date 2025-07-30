<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Rules\ValidMatchActivity;

class MatchActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'match_id',
        'team_id',
        'player_id',
        'activity',
        'time_activity',
        'detail'
    ];

    protected $hidden = ['deleted_at'];

    protected $casts = [
        'time_activity' => 'datetime:H:i:s'
    ];

    /**
     * Available activity types
     */
    public static function getActivityTypes()
    {
        return [
            // Match Control
            'match_start' => 'Pertandingan dimulai',
            'match_end' => 'Pertandingan berakhir',
            'half_time' => 'Istirahat babak pertama',
            'second_half' => 'Babak kedua dimulai',
            'extra_time_start' => 'Perpanjangan waktu dimulai',
            'extra_time_end' => 'Perpanjangan waktu berakhir',
            'penalty_shootout_start' => 'Adu penalti dimulai',
            'penalty_shootout_end' => 'Adu penalti berakhir',
            
            // Goals
            'goal' => 'Gol',
            'own_goal' => 'Gol bunuh diri',
            
            // Assists
            'assist' => 'Assist',
            
            // Cards
            'yellow_card' => 'Kartu kuning',
            'red_card' => 'Kartu merah',
            'second_yellow' => 'Kartu kuning kedua (merah)',
            'yellow_red_card' => 'Kartu kuning-merah',
            
            // Fouls & Violations
            'foul' => 'Pelanggaran',
            'dangerous_play' => 'Permainan berbahaya',
            'handball' => 'Handball',
            'offside' => 'Offside',
            'diving' => 'Simulasi/diving',
            'violent_conduct' => 'Kekerasan',
            'unsporting_behavior' => 'Perilaku tidak sportif',
            'dissent' => 'Protes kepada wasit',
            'time_wasting' => 'Membuang waktu',
            
            // Substitutions
            'substitution_in' => 'Pemain masuk',
            'substitution_out' => 'Pemain keluar',
            'tactical_substitution' => 'Substitusi taktis',
            'injury_substitution' => 'Substitusi cedera',
            
            // Injuries & Medical
            'injury' => 'Cedera',
            'medical_attention' => 'Pertolongan medis',
            'concussion' => 'Gegar otak',
            'blood_injury' => 'Cedera berdarah',
            
            // Set Pieces
            'corner' => 'Tendangan sudut',
            'free_kick' => 'Tendangan bebas',
            'penalty_awarded' => 'Penalti diberikan',
            'penalty_missed' => 'Penalti meleset',
            'penalty_saved' => 'Penalti diselamatkan',
            'throw_in' => 'Lemparan ke dalam',
            'goal_kick' => 'Tendangan gawang',
            
            // Goalkeeper Actions
            'save' => 'Penyelamatan',
            'catch' => 'Menangkap bola',
            'punch' => 'Meninju bola',
            'goalkeeper_foul' => 'Pelanggaran kiper',
            'goalkeeper_handball' => 'Handball kiper',
            
            // Other Events
            'ball_out' => 'Bola keluar',
            'ball_in_play' => 'Bola dalam permainan',
            'referee_decision' => 'Keputusan wasit',
            'var_check' => 'Pengecekan VAR',
            'var_decision' => 'Keputusan VAR',
            'goal_disallowed' => 'Gol dibatalkan',
            'goal_allowed' => 'Gol disahkan',
        ];
    }

    /**
     * Get validation rules for creating a match activity
     */
    public static function getCreateRules()
    {
        return [
            'match_id' => 'required|exists:matches,id',
            'team_id' => 'nullable|exists:teams,id',
            'player_id' => 'nullable|exists:players,id',
            'activity' => [
                'required',
                'string',
                'max:100',
                new ValidMatchActivity
            ],
            'time_activity' => 'required|date_format:H:i:s',
            'detail' => 'nullable|string|max:1000'
        ];
    }

    /**
     * Get validation rules for updating a match activity
     */
    public static function getUpdateRules($activityId = null)
    {
        $rules = [
            'match_id' => 'sometimes|required|exists:matches,id',
            'team_id' => 'nullable|exists:teams,id',
            'player_id' => 'nullable|exists:players,id',
            'activity' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                new ValidMatchActivity($activityId)
            ],
            'time_activity' => 'sometimes|required|date_format:H:i:s',
            'detail' => 'nullable|string|max:1000'
        ];

        return $rules;
    }

    /**
     * Scope to search activities by detail
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('detail', 'like', "%{$search}%");
        }
        return $query;
    }

    /**
     * Scope to filter by match
     */
    public function scopeByMatch($query, $matchId)
    {
        if ($matchId) {
            return $query->where('match_id', $matchId);
        }
        return $query;
    }

    /**
     * Scope to filter by team
     */
    public function scopeByTeam($query, $teamId)
    {
        if ($teamId) {
            return $query->where('team_id', $teamId);
        }
        return $query;
    }

    /**
     * Scope to filter by player
     */
    public function scopeByPlayer($query, $playerId)
    {
        if ($playerId) {
            return $query->where('player_id', $playerId);
        }
        return $query;
    }

    /**
     * Scope to filter by activity type
     */
    public function scopeByActivity($query, $activity)
    {
        if ($activity) {
            return $query->where('activity', $activity);
        }
        return $query;
    }

    /**
     * Scope to filter by time range
     */
    public function scopeByTimeRange($query, $startTime, $endTime)
    {
        if ($startTime) {
            $query->where('time_activity', '>=', $startTime);
        }
        if ($endTime) {
            $query->where('time_activity', '<=', $endTime);
        }
        return $query;
    }

    /**
     * Scope to get goals only
     */
    public function scopeGoals($query)
    {
        return $query->whereIn('activity', ['goal', 'own_goal']);
    }

    /**
     * Scope to get cards only
     */
    public function scopeCards($query)
    {
        return $query->whereIn('activity', ['yellow_card', 'red_card', 'second_yellow', 'yellow_red_card']);
    }

    /**
     * Scope to get substitutions only
     */
    public function scopeSubstitutions($query)
    {
        return $query->whereIn('activity', ['substitution_in', 'substitution_out', 'tactical_substitution', 'injury_substitution']);
    }

    /**
     * Relationship with Match
     */
    public function match()
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
    }

    /**
     * Relationship with Team
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Relationship with Player
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * Get match info attribute
     */
    public function getMatchInfoAttribute()
    {
        return $this->match ? [
            'id' => $this->match->id,
            'venue' => $this->match->venue,
            'match_datetime' => $this->match->match_datetime,
            'home_team' => $this->match->homeTeam ? $this->match->homeTeam->name : null,
            'away_team' => $this->match->awayTeam ? $this->match->awayTeam->name : null
        ] : null;
    }

    /**
     * Get team name attribute
     */
    public function getTeamNameAttribute()
    {
        return $this->team ? $this->team->name : null;
    }

    /**
     * Get player name attribute
     */
    public function getPlayerNameAttribute()
    {
        return $this->player ? $this->player->name : null;
    }

    /**
     * Get activity description attribute
     */
    public function getActivityDescriptionAttribute()
    {
        $activities = self::getActivityTypes();
        return $activities[$this->activity] ?? $this->activity;
    }
}
