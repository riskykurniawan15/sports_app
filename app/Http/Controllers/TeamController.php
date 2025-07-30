<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends ApiController
{
    /**
     * Display a listing of teams
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $city = $request->query('city');
        $perPage = $request->query('per_page', 10);
        
        $teams = Team::search($search)
            ->byCity($city)
            ->orderBy('name')
            ->paginate($perPage);
        
                    // Add city and province names to each team
            $teamsWithLocation = $teams->getCollection()->map(function ($team) {
                $team->city_name = $team->city_name;
                $team->province_name = $team->province_name;
                return $team;
            });

            return $this->successResponse($teamsWithLocation, 'Teams retrieved successfully', 200, [
                'pagination' => [
                    'current_page' => $teams->currentPage(),
                    'last_page' => $teams->lastPage(),
                    'per_page' => $teams->perPage(),
                    'total' => $teams->total(),
                    'from' => $teams->firstItem(),
                    'to' => $teams->lastItem()
                ],
                'filters' => [
                    'search' => $search,
                    'city' => $city
                ]
            ]);
    }

    /**
     * Store a newly created team
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Team::getCreateRules());
        
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }
        
        $team = Team::create($request->only([
            'name', 'logo', 'established_year', 'address', 'city'
        ]));
        
        return $this->createdResponse($team, 'Team created successfully');
    }

    /**
     * Display the specified team
     */
    public function show($id)
    {
        $team = Team::find($id);
        
        if (!$team) {
            return $this->notFoundResponse('Team not found');
        }
        
        // Add city and province names
        $team->city_name = $team->city_name;
        $team->province_name = $team->province_name;
        
        return $this->successResponse($team, 'Team retrieved successfully');
    }

    /**
     * Update the specified team
     */
    public function update(Request $request, $id)
    {
        $team = Team::find($id);
        
        if (!$team) {
            return $this->notFoundResponse('Team not found');
        }
        
        $validator = Validator::make($request->all(), Team::getUpdateRules());
        
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }
        
        $team->update($request->only([
            'name', 'logo', 'established_year', 'address', 'city'
        ]));
        
        // Add city and province names
        $team->city_name = $team->city_name;
        $team->province_name = $team->province_name;
        
        return $this->updatedResponse($team, 'Team updated successfully');
    }

    /**
     * Remove the specified team (soft delete)
     */
    public function destroy($id)
    {
        $team = Team::find($id);
        
        if (!$team) {
            return $this->notFoundResponse('Team not found');
        }
        
        $team->delete(); // Soft delete
        
        return $this->deletedResponse('Team deleted successfully');
    }
}
