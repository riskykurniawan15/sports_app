<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Rules\ValidSquadNumber;

class Player extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'team_id',
        'squad_number',
        'height',
        'weight',
        'position_id'
    ];

    protected $hidden = ['deleted_at'];

    protected $casts = [
        'height' => 'integer',
        'weight' => 'integer',
        'squad_number' => 'integer'
    ];

    /**
     * Get validation rules for creating a player
     */
    public static function getCreateRules()
    {
        return [
            'name' => 'required|string|max:100',
            'team_id' => 'required|exists:teams,id',
            'squad_number' => [
                'required',
                'integer',
                'min:1',
                'max:99',
                new ValidSquadNumber
            ],
            'height' => 'required|integer|min:100|max:250', // 100cm - 250cm
            'weight' => 'required|integer|min:30|max:150',  // 30kg - 150kg
            'position_id' => 'required|exists:positions,id'
        ];
    }

    /**
     * Get validation rules for updating a player
     */
    public static function getUpdateRules($playerId = null)
    {
        $rules = [
            'name' => 'sometimes|required|string|max:100',
            'team_id' => 'sometimes|required|exists:teams,id',
            'squad_number' => [
                'sometimes',
                'required',
                'integer',
                'min:1',
                'max:99',
                new ValidSquadNumber($playerId)
            ],
            'height' => 'sometimes|required|integer|min:100|max:250',
            'weight' => 'sometimes|required|integer|min:30|max:150',
            'position_id' => 'sometimes|required|exists:positions,id'
        ];

        return $rules;
    }

    /**
     * Scope to search players by name
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('name', 'like', "%{$search}%");
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
     * Scope to filter by position
     */
    public function scopeByPosition($query, $positionId)
    {
        if ($positionId) {
            return $query->where('position_id', $positionId);
        }
        return $query;
    }

    /**
     * Scope to filter by squad number
     */
    public function scopeBySquadNumber($query, $squadNumber)
    {
        if ($squadNumber) {
            return $query->where('squad_number', $squadNumber);
        }
        return $query;
    }

    /**
     * Relationship with Team
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Relationship with Position
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Get team name attribute
     */
    public function getTeamNameAttribute()
    {
        return $this->team ? $this->team->name : null;
    }

    /**
     * Get position name attribute
     */
    public function getPositionNameAttribute()
    {
        return $this->position ? $this->position->name : null;
    }

    /**
     * Get position code attribute
     */
    public function getPositionCodeAttribute()
    {
        return $this->position ? $this->position->code : null;
    }
}
