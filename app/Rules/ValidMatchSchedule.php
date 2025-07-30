<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\GameMatch;
use Carbon\Carbon;

class ValidMatchSchedule implements ValidationRule
{
    protected $matchId;

    public function __construct($matchId = null)
    {
        $this->matchId = $matchId;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $homeTeamId = request()->input('home_team_id');
        $awayTeamId = request()->input('away_team_id');
        
        if (!$homeTeamId || !$awayTeamId) {
            $fail('Home team and away team are required to validate schedule.');
            return;
        }

        $matchDateTime = Carbon::parse($value);
        $conflictStart = $matchDateTime->copy()->subHours(2);
        $conflictEnd = $matchDateTime->copy()->addHours(2);

        // Check for conflicts with home team
        $homeTeamConflicts = GameMatch::where('home_team_id', $homeTeamId)
            ->whereBetween('match_datetime', [$conflictStart, $conflictEnd]);

        // Check for conflicts with away team
        $awayTeamConflicts = GameMatch::where('away_team_id', $awayTeamId)
            ->whereBetween('match_datetime', [$conflictStart, $conflictEnd]);

        // Also check if away team is playing as home team in the same time range
        $awayTeamAsHomeConflicts = GameMatch::where('home_team_id', $awayTeamId)
            ->whereBetween('match_datetime', [$conflictStart, $conflictEnd]);

        // Also check if home team is playing as away team in the same time range
        $homeTeamAsAwayConflicts = GameMatch::where('away_team_id', $homeTeamId)
            ->whereBetween('match_datetime', [$conflictStart, $conflictEnd]);

        // Exclude current match if updating
        if ($this->matchId) {
            $homeTeamConflicts->where('id', '!=', $this->matchId);
            $awayTeamConflicts->where('id', '!=', $this->matchId);
            $awayTeamAsHomeConflicts->where('id', '!=', $this->matchId);
            $homeTeamAsAwayConflicts->where('id', '!=', $this->matchId);
        }

        // Check if there are any conflicts
        if ($homeTeamConflicts->exists()) {
            $fail('Home team has another match within 2 hours of this schedule.');
            return;
        }

        if ($awayTeamConflicts->exists()) {
            $fail('Away team has another match within 2 hours of this schedule.');
            return;
        }

        if ($awayTeamAsHomeConflicts->exists()) {
            $fail('Away team has another match within 2 hours of this schedule.');
            return;
        }

        if ($homeTeamAsAwayConflicts->exists()) {
            $fail('Home team has another match within 2 hours of this schedule.');
            return;
        }
    }
}
