<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WilayahService;

class WilayahController extends ApiController
{
    protected $wilayahService;

    public function __construct(WilayahService $wilayahService)
    {
        $this->wilayahService = $wilayahService;
    }

    /**
     * Get all provinces with optional search
     */
    public function getProvinces(Request $request)
    {
        $search = $request->query('search');
        
        $provinces = $this->wilayahService->getProvinces($search);
        
        if ($provinces === null) {
            return $this->serverErrorResponse('Failed to fetch provinces data');
        }

        $message = $search 
            ? "Provinces filtered by search: {$search}"
            : 'All provinces retrieved successfully';

        return $this->successResponse($provinces, $message, 200, [
            'total' => count($provinces),
            'search' => $search,
            'cache_info' => $this->wilayahService->getCacheStatus()
        ]);
    }

    /**
     * Clear provinces cache
     */
    public function clearCache()
    {
        $this->wilayahService->clearProvincesCache();
        
        return $this->successResponse(null, 'Provinces cache cleared successfully');
    }

    /**
     * Get regencies by province code
     */
    public function getRegencies(Request $request, $provinceCode)
    {
        $search = $request->query('search');
        
        $data = $this->wilayahService->getRegenciesWithProvince($provinceCode, $search);
        
        if ($data === null) {
            return $this->notFoundResponse('Province not found or failed to fetch regencies data');
        }

        $message = $search 
            ? "Regencies filtered by search: {$search}"
            : 'Regencies retrieved successfully';

        return $this->successResponse([
            'province' => $data['province'],
            'regencies' => $data['regencies']
        ], $message, 200, [
            'total' => count($data['regencies']),
            'search' => $search,
            'cache_info' => $this->wilayahService->getCacheStatus()
        ]);
    }

    /**
     * Clear regencies cache for specific province
     */
    public function clearRegenciesCache($provinceCode)
    {
        $this->wilayahService->clearRegenciesCache($provinceCode);
        
        return $this->successResponse(null, "Regencies cache cleared for province code: {$provinceCode}");
    }

    /**
     * Get cache status
     */
    public function getCacheStatus()
    {
        return $this->successResponse(
            $this->wilayahService->getCacheStatus(),
            'Cache status retrieved successfully'
        );
    }
} 