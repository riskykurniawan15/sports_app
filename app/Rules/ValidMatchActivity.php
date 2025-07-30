<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\MatchActivity;
use App\Models\GameMatch;

class ValidMatchActivity implements ValidationRule
{
    protected $activityId;

    public function __construct($activityId = null)
    {
        $this->activityId = $activityId;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validActivities = array_keys(MatchActivity::getActivityTypes());
        
        if (!in_array($value, $validActivities)) {
            $fail("The activity '{$value}' is not a valid match activity type.");
            return;
        }

        // Get match_id from request
        $matchId = request()->input('match_id');
        if (!$matchId) {
            $fail('Match ID is required to validate activity.');
            return;
        }

        // Check if match exists
        $match = GameMatch::find($matchId);
        if (!$match) {
            $fail('Match not found.');
            return;
        }

        // For match_start and match_end, ensure only one record per match
        if (in_array($value, ['match_start', 'match_end'])) {
            $existingActivity = MatchActivity::where('match_id', $matchId)
                ->where('activity', $value);

            if ($this->activityId) {
                $existingActivity->where('id', '!=', $this->activityId);
            }

            if ($existingActivity->exists()) {
                $fail("Activity '{$value}' already exists for this match.");
                return;
            }
        }

        // Check match status for other activities
        if (!in_array($value, ['match_start', 'match_end'])) {
            $matchStart = MatchActivity::where('match_id', $matchId)
                ->where('activity', 'match_start')
                ->exists();

            $matchEnd = MatchActivity::where('match_id', $matchId)
                ->where('activity', 'match_end')
                ->exists();

            if (!$matchStart) {
                $fail('Cannot add activity: Match has not started yet.');
                return;
            }

            if ($matchEnd) {
                $fail('Cannot add activity: Match has already ended.');
                return;
            }

            // Validate team_id belongs to the match
            $teamId = request()->input('team_id');
            if ($teamId && $match) {
                if ($teamId != $match->home_team_id && $teamId != $match->away_team_id) {
                    $fail('Team ID must belong to one of the teams in this match.');
                    return;
                }
            }

            // Validate player_id belongs to the team (if both are provided)
            $playerId = request()->input('player_id');
            if ($teamId && $playerId) {
                $player = \App\Models\Player::where('id', $playerId)
                    ->where('team_id', $teamId)
                    ->first();
                
                if (!$player) {
                    $fail('Player ID must belong to the specified team.');
                    return;
                }
            }
        }
    }
}
