<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Player;

class ValidSquadNumber implements ValidationRule
{
    protected $playerId;

    public function __construct($playerId = null)
    {
        $this->playerId = $playerId;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $teamId = request()->input('team_id');
        
        if (!$teamId) {
            $fail('Team ID is required to validate squad number.');
            return;
        }

        // Check if squad number already exists for this team (excluding current player if updating)
        $query = Player::where('team_id', $teamId)
                      ->where('squad_number', $value);

        if ($this->playerId) {
            $query->where('id', '!=', $this->playerId);
        }

        if ($query->exists()) {
            $fail("Squad number {$value} is already taken by another player in this team.");
        }
    }
}
