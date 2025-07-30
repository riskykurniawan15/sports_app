<?php

namespace App\Http\Controllers;

use App\Models\MatchActivity;
use App\Models\GameMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
}
