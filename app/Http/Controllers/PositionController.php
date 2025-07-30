<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends ApiController
{
    /**
     * Display a listing of positions
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $perPage = $request->query('per_page', 50); // Default 50 karena data positions tidak terlalu banyak
        
        $positions = Position::search($search)
            ->byCategory($category)
            ->orderBy('code')
            ->paginate($perPage);
        
        return $this->successResponse($positions->items(), 'Positions retrieved successfully', 200, [
            'pagination' => [
                'current_page' => $positions->currentPage(),
                'last_page' => $positions->lastPage(),
                'per_page' => $positions->perPage(),
                'total' => $positions->total(),
                'from' => $positions->firstItem(),
                'to' => $positions->lastItem()
            ],
            'filters' => [
                'search' => $search,
                'category' => $category
            ]
        ]);
    }

    /**
     * Display the specified position
     */
    public function show($id)
    {
        $position = Position::find($id);
        
        if (!$position) {
            return $this->notFoundResponse('Position not found');
        }
        
        return $this->successResponse($position, 'Position retrieved successfully');
    }
}
