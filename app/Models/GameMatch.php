<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Rules\ValidMatchSchedule;

class GameMatch extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'matches';

    protected $fillable = [
        'venue',
        'match_datetime',
        'home_team_id',
        'away_team_id',
        'match_metadata'
    ];

    protected $hidden = ['deleted_at'];

    protected $casts = [
        'match_datetime' => 'datetime',
        'match_metadata' => 'array'
    ];

    /**
     * Get validation rules for creating a match
     */
    public static function getCreateRules()
    {
        return [
            'venue' => 'required|string|max:200',
            'match_datetime' => [
                'required',
                'date',
                'after:now',
                new ValidMatchSchedule
            ],
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'match_metadata' => 'nullable|array'
        ];
    }

    /**
     * Get validation rules for updating a match
     */
    public static function getUpdateRules($matchId = null)
    {
        $rules = [
            'venue' => 'sometimes|required|string|max:200',
            'match_datetime' => [
                'sometimes',
                'required',
                'date',
                'after:now',
                new ValidMatchSchedule($matchId)
            ],
            'home_team_id' => 'sometimes|required|exists:teams,id',
            'away_team_id' => 'sometimes|required|exists:teams,id|different:home_team_id',
            'match_metadata' => 'nullable|array'
        ];

        return $rules;
    }

    /**
     * Scope to search matches by venue
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('venue', 'like', "%{$search}%");
        }
        return $query;
    }

    /**
     * Scope to filter by home team
     */
    public function scopeByHomeTeam($query, $teamId)
    {
        if ($teamId) {
            return $query->where('home_team_id', $teamId);
        }
        return $query;
    }

    /**
     * Scope to filter by away team
     */
    public function scopeByAwayTeam($query, $teamId)
    {
        if ($teamId) {
            return $query->where('away_team_id', $teamId);
        }
        return $query;
    }

    /**
     * Scope to filter by team (either home or away)
     */
    public function scopeByTeam($query, $teamId)
    {
        if ($teamId) {
            return $query->where(function ($q) use ($teamId) {
                $q->where('home_team_id', $teamId)
                  ->orWhere('away_team_id', $teamId);
            });
        }
        return $query;
    }

    /**
     * Scope to filter by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        if ($startDate) {
            $query->where('match_datetime', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('match_datetime', '<=', $endDate);
        }
        return $query;
    }

    /**
     * Scope to get upcoming matches
     */
    public function scopeUpcoming($query)
    {
        return $query->where('match_datetime', '>', now());
    }

    /**
     * Scope to get past matches
     */
    public function scopePast($query)
    {
        return $query->where('match_datetime', '<', now());
    }

    /**
     * Relationship with Home Team
     */
    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    /**
     * Relationship with Away Team
     */
    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    /**
     * Get home team name attribute
     */
    public function getHomeTeamNameAttribute()
    {
        return $this->homeTeam ? $this->homeTeam->name : null;
    }

    /**
     * Get away team name attribute
     */
    public function getAwayTeamNameAttribute()
    {
        return $this->awayTeam ? $this->awayTeam->name : null;
    }

    /**
     * Get formatted match date attribute
     */
    public function getFormattedDateAttribute()
    {
        return $this->match_datetime ? $this->match_datetime->format('Y-m-d H:i:s') : null;
    }

    /**
     * Get match status attribute
     */
    public function getMatchStatusAttribute()
    {
        if (!$this->match_datetime) {
            return 'unknown';
        }
        
        return $this->match_datetime->isPast() ? 'completed' : 'upcoming';
    }
}
