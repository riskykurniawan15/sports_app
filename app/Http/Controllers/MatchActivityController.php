<?php

namespace App\Http\Controllers;

use App\Models\MatchActivity;
use App\Models\GameMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MatchActivityController extends ApiController
{
    /**
     * Display a listing of match activities
     */
    public function index(Request $request, $matchId)
    {
        // Validate match exists
        $match = GameMatch::find($matchId);
        if (!$match) {
            return $this->notFoundResponse('Match not found');
        }
        
        $search = $request->query('search');
        $teamId = $request->query('team_id');
        $playerId = $request->query('player_id');
        $activity = $request->query('activity');
        $startTime = $request->query('start_time');
        $endTime = $request->query('end_time');
        $type = $request->query('type'); // goals, cards, substitutions, all
        $perPage = $request->query('per_page', 15);
        
        $activities = MatchActivity::with(['match', 'team', 'player'])
            ->search($search)
            ->byMatch($matchId)
            ->byTeam($teamId)
            ->byPlayer($playerId)
            ->byActivity($activity)
            ->byTimeRange($startTime, $endTime);
        
        // Apply type filter
        if ($type === 'goals') {
            $activities->goals();
        } elseif ($type === 'cards') {
            $activities->cards();
        } elseif ($type === 'substitutions') {
            $activities->substitutions();
        }
        
        $activities = $activities->orderBy('time_activity', 'desc')
            ->paginate($perPage);
        
        return $this->successResponse($activities->items(), 'Match activities retrieved successfully', 200, [
            'pagination' => [
                'current_page' => $activities->currentPage(),
                'last_page' => $activities->lastPage(),
                'per_page' => $activities->perPage(),
                'total' => $activities->total(),
                'from' => $activities->firstItem(),
                'to' => $activities->lastItem()
            ],
            'filters' => [
                'search' => $search,
                'match_id' => $matchId,
                'team_id' => $teamId,
                'player_id' => $playerId,
                'activity' => $activity,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'type' => $type
            ]
        ]);
    }

    /**
     * Store a newly created match activity
     */
    public function store(Request $request, $matchId)
    {
        // Validate match exists
        $match = GameMatch::find($matchId);
        if (!$match) {
            return $this->notFoundResponse('Match not found');
        }

        // Add match_id to request data
        $request->merge(['match_id' => $matchId]);
        
        $validator = Validator::make($request->all(), MatchActivity::getCreateRules());
        
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }
        
        $activity = MatchActivity::create($request->all());
        $activity->load(['match', 'team', 'player']);
        
        // If this is match_end activity, calculate and update match result
        if ($activity->activity === 'match_end') {
            $match = GameMatch::find($activity->match_id);
            if ($match) {
                $result = $match->calculateMatchResult();
                $activity->match->refresh(); // Refresh to get updated metadata
            }
        }
        
        return $this->createdResponse($activity, 'Match activity created successfully');
    }

    /**
     * Display the specified match activity
     */
    public function show($matchId, $id)
    {
        // Validate match exists
        $match = GameMatch::find($matchId);
        if (!$match) {
            return $this->notFoundResponse('Match not found');
        }

        $activity = MatchActivity::with(['match', 'team', 'player'])
            ->where('match_id', $matchId)
            ->where('id', $id)
            ->first();
        
        if (!$activity) {
            return $this->notFoundResponse('Match activity not found');
        }
        
        return $this->successResponse($activity, 'Match activity retrieved successfully');
    }

    /**
     * Remove the specified match activity (soft delete)
     */
    public function destroy($matchId, $id)
    {
        // Validate match exists
        $match = GameMatch::find($matchId);
        if (!$match) {
            return $this->notFoundResponse('Match not found');
        }

        $activity = MatchActivity::where('match_id', $matchId)
            ->where('id', $id)
            ->first();
        
        if (!$activity) {
            return $this->notFoundResponse('Match activity not found');
        }
        
        $activity->delete();
        
        return $this->deletedResponse('Match activity deleted successfully');
    }

    /**
     * Get available activity types
     */
    public function activityTypes()
    {
        $types = MatchActivity::getActivityTypes();
        
        return $this->successResponse($types, 'Activity types retrieved successfully');
    }

    /**
     * Get match timeline (activities for specific match)
     */
    public function matchTimeline($matchId)
    {
        $activities = MatchActivity::with(['team', 'player'])
            ->byMatch($matchId)
            ->orderBy('time_activity', 'desc')
            ->get();
        
        return $this->successResponse($activities, 'Match timeline retrieved successfully');
    }

    /**
     * Get match statistics
     */
    public function matchStats($matchId)
    {
        $stats = [
            'goals' => MatchActivity::byMatch($matchId)->goals()->count(),
            'yellow_cards' => MatchActivity::byMatch($matchId)->where('activity', 'yellow_card')->count(),
            'red_cards' => MatchActivity::byMatch($matchId)->whereIn('activity', ['red_card', 'second_yellow'])->count(),
            'substitutions' => MatchActivity::byMatch($matchId)->substitutions()->count(),
            'corners' => MatchActivity::byMatch($matchId)->where('activity', 'corner')->count(),
            'penalties' => MatchActivity::byMatch($matchId)->whereIn('activity', ['penalty_goal', 'penalty_missed'])->count(),
            'fouls' => MatchActivity::byMatch($matchId)->where('activity', 'foul')->count(),
        ];
        
        return $this->successResponse($stats, 'Match statistics retrieved successfully');
    }

    /**
     * Get comprehensive match report
     */
    public function matchReport($matchId)
    {
        // Validate match exists
        $match = GameMatch::with(['homeTeam', 'awayTeam'])->find($matchId);
        if (!$match) {
            return $this->notFoundResponse('Match not found');
        }

        // Get all goal activities with player info
        $goalActivities = MatchActivity::with(['player', 'team'])
            ->where('match_id', $matchId)
            ->whereIn('activity', ['goal', 'own_goal'])
            ->orderBy('time_activity', 'asc')
            ->get();

        // Calculate goals per team
        $homeRegularGoals = MatchActivity::where('match_id', $matchId)
            ->where('team_id', $match->home_team_id)
            ->where('activity', 'goal')
            ->count();

        $awayRegularGoals = MatchActivity::where('match_id', $matchId)
            ->where('team_id', $match->away_team_id)
            ->where('activity', 'goal')
            ->count();

        $homeOwnGoals = MatchActivity::where('match_id', $matchId)
            ->where('team_id', $match->home_team_id)
            ->where('activity', 'own_goal')
            ->count();

        $awayOwnGoals = MatchActivity::where('match_id', $matchId)
            ->where('team_id', $match->away_team_id)
            ->where('activity', 'own_goal')
            ->count();

        $finalHomeScore = $homeRegularGoals + $awayOwnGoals;
        $finalAwayScore = $awayRegularGoals + $homeOwnGoals;

        // Determine match result
        $homeWin = $finalHomeScore > $finalAwayScore;
        $draw = $finalHomeScore == $finalAwayScore;

        // Calculate team performance from all matches (from match datetime + 2 hours)
        $matchDateTime = $match->match_datetime;
        $cutoffDateTime = date('Y-m-d H:i:s', strtotime($matchDateTime . ' +2 hours'));

        // Home team performance
        $homeTeamMatches = GameMatch::where(function($query) use ($match) {
                $query->where('home_team_id', $match->home_team_id)
                      ->orWhere('away_team_id', $match->home_team_id);
            })
            ->where('match_datetime', '<=', $cutoffDateTime)
            ->get();

        $homeWins = 0;
        $homeLosses = 0;
        $homeDraws = 0;

        foreach ($homeTeamMatches as $homeMatch) {
            if (!$homeMatch->match_metadata || !isset($homeMatch->match_metadata['status'])) {
                continue;
            }

            $winnerTeam = $homeMatch->match_metadata['winner_team'] ?? null;

            if ($winnerTeam === null) {
                // Draw
                $homeDraws++;
            } elseif ($winnerTeam == $match->home_team_id) {
                // Home team won
                $homeWins++;
            } else {
                // Away team won
                $homeLosses++;
            }
        }

        // Away team performance
        $awayTeamMatches = GameMatch::where(function($query) use ($match) {
                $query->where('home_team_id', $match->away_team_id)
                      ->orWhere('away_team_id', $match->away_team_id);
            })
            ->where('match_datetime', '<=', $cutoffDateTime)
            ->get();

        $awayWins = 0;
        $awayLosses = 0;
        $awayDraws = 0;

        foreach ($awayTeamMatches as $awayMatch) {
            if (!$awayMatch->match_metadata || !isset($awayMatch->match_metadata['status'])) {
                continue;
            }

            $winnerTeam = $awayMatch->match_metadata['winner_team'] ?? null;

            if ($winnerTeam === null) {
                // Draw
                $awayDraws++;
            } elseif ($winnerTeam == $match->away_team_id) {
                // Away team won
                $awayWins++;
            } else {
                // Home team won
                $awayLosses++;
            }
        }

        // Get top scorers
        $topScorers = MatchActivity::with(['player', 'team'])
            ->where('match_id', $matchId)
            ->where('activity', 'goal')
            ->whereNotNull('player_id')
            ->select('player_id', 'team_id', DB::raw('count(*) as goal_count'))
            ->groupBy('player_id', 'team_id')
            ->orderBy('goal_count', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'player_id' => $item->player_id,
                    'player_name' => $item->player ? $item->player->name : 'Unknown',
                    'team_id' => $item->team_id,
                    'team_name' => $item->team ? $item->team->name : 'Unknown',
                    'goals' => $item->goal_count
                ];
            });

        // Format goal timeline
        $goalTimeline = $goalActivities->map(function ($activity) use ($match) {
            $isOwnGoal = $activity->activity === 'own_goal';
            $scoringTeam = $isOwnGoal ? 
                ($activity->team_id == $match->home_team_id ? $match->awayTeam : $match->homeTeam) :
                $activity->team;
            
            return [
                'time' => $activity->time_activity->format('H:i:s'),
                'player_id' => $activity->player_id,
                'player_name' => $activity->player ? $activity->player->name : 'Unknown',
                'team_id' => $scoringTeam ? $scoringTeam->id : null,
                'team_name' => $scoringTeam ? $scoringTeam->name : 'Unknown',
                'activity' => $activity->activity,
                'activity_description' => $activity->activity_description,
                'detail' => $activity->detail,
                'is_own_goal' => $isOwnGoal,
                'own_goal_player' => $isOwnGoal ? ($activity->player ? $activity->player->name : 'Unknown') : null
            ];
        });

        $report = [
            'match_info' => [
                'id' => $match->id,
                'venue' => $match->venue,
                'match_datetime' => $match->match_datetime,
                'home_team' => [
                    'id' => $match->homeTeam ? $match->homeTeam->id : null,
                    'name' => $match->homeTeam ? $match->homeTeam->name : 'Unknown'
                ],
                'away_team' => [
                    'id' => $match->awayTeam ? $match->awayTeam->id : null,
                    'name' => $match->awayTeam ? $match->awayTeam->name : 'Unknown'
                ]
            ],
            'match_result' => [
                'final_score' => "{$finalHomeScore} - {$finalAwayScore}",
                'home_score' => $finalHomeScore,
                'away_score' => $finalAwayScore,
                'result' => $draw ? 'draw' : ($homeWin ? 'home-win' : 'away-win'),
                'winner_team' => $draw ? null : ($homeWin ? $match->home_team_id : $match->away_team_id)
            ],
            'team_performance' => [
                'home_team' => [
                    'wins' => $homeWins,
                    'losses' => $homeLosses,
                    'draws' => $homeDraws,
                    'total_matches' => $homeWins + $homeLosses + $homeDraws,
                    'regular_goals' => $homeRegularGoals,
                    'own_goals_conceded' => $homeOwnGoals
                ],
                'away_team' => [
                    'wins' => $awayWins,
                    'losses' => $awayLosses,
                    'draws' => $awayDraws,
                    'total_matches' => $awayWins + $awayLosses + $awayDraws,
                    'regular_goals' => $awayRegularGoals,
                    'own_goals_conceded' => $awayOwnGoals
                ]
            ],
            'goal_timeline' => $goalTimeline,
            'top_scorers' => $topScorers,
            'match_metadata' => $match->match_metadata
        ];

        return $this->successResponse($report, 'Match report retrieved successfully');
    }
}
