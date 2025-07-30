<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PlayerController extends ApiController
{
    /**
     * Display a listing of players
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $teamId = $request->query('team_id');
        $positionId = $request->query('position_id');
        $squadNumber = $request->query('squad_number');
        $perPage = $request->query('per_page', 15);
        
        $players = Player::with(['team', 'position'])
            ->search($search)
            ->byTeam($teamId)
            ->byPosition($positionId)
            ->bySquadNumber($squadNumber)
            ->orderBy('name')
            ->paginate($perPage);
        
        // Add team_name and position_name to each player
        $playersData = $players->items();
        
        // Ensure team and position data is properly loaded
        foreach ($playersData as $player) {
            if (!$player->team) {
                $player->team = null;
            }
            if (!$player->position) {
                $player->position = null;
            }
        }
        
        return $this->successResponse($playersData, 'Players retrieved successfully', 200, [
            'pagination' => [
                'current_page' => $players->currentPage(),
                'last_page' => $players->lastPage(),
                'per_page' => $players->perPage(),
                'total' => $players->total(),
                'from' => $players->firstItem(),
                'to' => $players->lastItem()
            ],
            'filters' => [
                'search' => $search,
                'team_id' => $teamId,
                'position_id' => $positionId,
                'squad_number' => $squadNumber
            ]
        ]);
    }

    /**
     * Store a newly created player
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Player::getCreateRules());
        
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }
        
        $player = Player::create($request->all());
        $player->load(['team', 'position']);
        
        return $this->createdResponse($player, 'Player created successfully');
    }

    /**
     * Display the specified player
     */
    public function show($id)
    {
        $player = Player::with(['team', 'position'])->find($id);
        
        if (!$player) {
            return $this->notFoundResponse('Player not found');
        }
        
        return $this->successResponse($player, 'Player retrieved successfully');
    }

    /**
     * Update the specified player
     */
    public function update(Request $request, $id)
    {
        $player = Player::find($id);
        
        if (!$player) {
            return $this->notFoundResponse('Player not found');
        }
        
        $validator = Validator::make($request->all(), Player::getUpdateRules($id));
        
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }
        
        $player->fill($request->all());
        $player->save();
        $player->load(['team', 'position']);
        
        return $this->updatedResponse($player, 'Player updated successfully');
    }

    /**
     * Remove the specified player (soft delete)
     */
    public function destroy($id)
    {
        $player = Player::find($id);
        
        if (!$player) {
            return $this->notFoundResponse('Player not found');
        }
        
        $player->delete();
        
        return $this->deletedResponse('Player deleted successfully');
    }
}
