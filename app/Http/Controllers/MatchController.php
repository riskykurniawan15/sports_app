<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MatchController extends ApiController
{
    /**
     * Display a listing of matches
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $homeTeamId = $request->query('home_team_id');
        $awayTeamId = $request->query('away_team_id');
        $teamId = $request->query('team_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $status = $request->query('status'); // upcoming, past, all
        $perPage = $request->query('per_page', 15);
        
        $matches = GameMatch::with(['homeTeam', 'awayTeam'])
            ->search($search)
            ->byHomeTeam($homeTeamId)
            ->byAwayTeam($awayTeamId)
            ->byTeam($teamId)
            ->byDateRange($startDate, $endDate);
        
        // Apply status filter
        if ($status === 'upcoming') {
            $matches->upcoming();
        } elseif ($status === 'past') {
            $matches->past();
        }
        
        $matches = $matches->orderBy('match_datetime', 'desc')
            ->paginate($perPage);
        
        return $this->successResponse($matches->items(), 'Matches retrieved successfully', 200, [
            'pagination' => [
                'current_page' => $matches->currentPage(),
                'last_page' => $matches->lastPage(),
                'per_page' => $matches->perPage(),
                'total' => $matches->total(),
                'from' => $matches->firstItem(),
                'to' => $matches->lastItem()
            ],
            'filters' => [
                'search' => $search,
                'home_team_id' => $homeTeamId,
                'away_team_id' => $awayTeamId,
                'team_id' => $teamId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $status
            ]
        ]);
    }

    /**
     * Store a newly created match
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), GameMatch::getCreateRules());
        
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }
        
        $match = GameMatch::create($request->all());
        $match->load(['homeTeam', 'awayTeam']);
        
        return $this->createdResponse($match, 'Match created successfully');
    }

    /**
     * Display the specified match
     */
    public function show($id)
    {
        $match = GameMatch::with(['homeTeam', 'awayTeam'])->find($id);
        
        if (!$match) {
            return $this->notFoundResponse('Match not found');
        }
        
        return $this->successResponse($match, 'Match retrieved successfully');
    }

    /**
     * Update the specified match
     */
    public function update(Request $request, $id)
    {
        $match = GameMatch::find($id);
        
        if (!$match) {
            return $this->notFoundResponse('Match not found');
        }
        
        $validator = Validator::make($request->all(), GameMatch::getUpdateRules($id));
        
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }
        
        $match->fill($request->all());
        $match->save();
        $match->load(['homeTeam', 'awayTeam']);
        
        return $this->updatedResponse($match, 'Match updated successfully');
    }

    /**
     * Remove the specified match (soft delete)
     */
    public function destroy($id)
    {
        $match = GameMatch::find($id);
        
        if (!$match) {
            return $this->notFoundResponse('Match not found');
        }
        
        $match->delete();
        
        return $this->deletedResponse('Match deleted successfully');
    }
}
