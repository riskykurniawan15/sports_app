<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WilayahService
{
    private $baseUrl = 'https://wilayah.id/api';
    private $cacheTime = 3600*24; // 1 day

    /**
     * Get all provinces with caching
     */
    public function getProvinces($search = null)
    {
        $cacheKey = 'wilayah_provinces';
        
        // Get from cache first
        $provinces = Cache::get($cacheKey);
        
        if (!$provinces) {
            try {
                $response = Http::timeout(10)->get($this->baseUrl . '/provinces.json');
                
                if ($response->successful()) {
                    $responseData = $response->json();
                    
                    // Extract provinces from the 'data' key
                    $provinces = $responseData['data'] ?? [];
                    
                    // Log the structure for debugging
                    Log::info('Provinces API response structure:', [
                        'response_keys' => array_keys($responseData),
                        'provinces_count' => count($provinces),
                        'first_province' => $provinces[0] ?? 'No data',
                        'meta' => $responseData['meta'] ?? 'No meta'
                    ]);
                    
                    // Cache the data
                    Cache::put($cacheKey, $provinces, $this->cacheTime);
                } else {
                    Log::error('Failed to fetch provinces from API', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    return null;
                }
            } catch (\Exception $e) {
                Log::error('Exception fetching provinces', [
                    'message' => $e->getMessage()
                ]);
                return null;
            }
        }

        // dd($provinces);

        // Filter by search if provided
        if ($search) {
            $provinces = $this->filterProvinces($provinces, $search);
        }

        return $provinces;
    }

    /**
     * Filter provinces by name
     */
    private function filterProvinces($provinces, $search)
    {
        return collect($provinces)->filter(function ($province) use ($search) {
            // Check if name key exists and is not null
            if (!isset($province['name']) || empty($province['name'])) {
                return false;
            }
            return stripos(strtolower($province['name']), strtolower($search)) !== false;
        })->values()->all();
    }

    /**
     * Clear provinces cache
     */
    public function clearProvincesCache()
    {
        Cache::forget('wilayah_provinces');
        return true;
    }

    /**
     * Get regencies by province code
     */
    public function getRegencies($provinceCode, $search = null)
    {
        $cacheKey = "wilayah_regencies_{$provinceCode}";
        
        // Get from cache first
        $regencies = Cache::get($cacheKey);
        
        if (!$regencies) {
            try {
                $response = Http::timeout(10)->get($this->baseUrl . "/regencies/{$provinceCode}.json");
                
                if ($response->successful()) {
                    $responseData = $response->json();
                    
                    // Extract regencies from the 'data' key
                    $regencies = $responseData['data'] ?? [];
                    
                    // Log the structure for debugging
                    Log::info('Regencies API response structure:', [
                        'province_code' => $provinceCode,
                        'response_keys' => array_keys($responseData),
                        'regencies_count' => count($regencies),
                        'first_regency' => $regencies[0] ?? 'No data',
                        'meta' => $responseData['meta'] ?? 'No meta'
                    ]);
                    
                    // Cache the data
                    Cache::put($cacheKey, $regencies, $this->cacheTime);
                } else {
                    Log::error('Failed to fetch regencies from API', [
                        'province_code' => $provinceCode,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    return null;
                }
            } catch (\Exception $e) {
                Log::error('Exception fetching regencies', [
                    'province_code' => $provinceCode,
                    'message' => $e->getMessage()
                ]);
                return null;
            }
        }

        // Filter by search if provided
        if ($search) {
            $regencies = $this->filterRegencies($regencies, $search);
        }

        return $regencies;
    }

    /**
     * Get regencies with province info
     */
    public function getRegenciesWithProvince($provinceCode, $search = null)
    {
        // Get province name first
        $provinceName = $this->getProvinceName($provinceCode);
        
        // Get regencies
        $regencies = $this->getRegencies($provinceCode, $search);
        
        if ($regencies === null) {
            return null;
        }

        return [
            'province' => [
                'code' => $provinceCode,
                'name' => $provinceName
            ],
            'regencies' => $regencies
        ];
    }

    /**
     * Get province name by code
     */
    private function getProvinceName($provinceCode)
    {
        $provinces = $this->getProvinces();
        
        if (!$provinces) {
            return 'Unknown Province';
        }

        $province = collect($provinces)->firstWhere('code', $provinceCode);
        
        return $province ? $province['name'] : 'Unknown Province';
    }

    /**
     * Filter regencies by name
     */
    private function filterRegencies($regencies, $search)
    {
        return collect($regencies)->filter(function ($regency) use ($search) {
            // Check if name key exists and is not null
            if (!isset($regency['name']) || empty($regency['name'])) {
                return false;
            }
            return stripos(strtolower($regency['name']), strtolower($search)) !== false;
        })->values()->all();
    }

    /**
     * Clear regencies cache for specific province
     */
    public function clearRegenciesCache($provinceCode)
    {
        $cacheKey = "wilayah_regencies_{$provinceCode}";
        Cache::forget($cacheKey);
        return true;
    }

    /**
     * Clear all regencies cache
     */
    public function clearAllRegenciesCache()
    {
        // Get all cache keys that start with wilayah_regencies_
        $keys = Cache::get('wilayah_regencies_keys', []);
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        Cache::forget('wilayah_regencies_keys');
        return true;
    }

    /**
     * Get cache status
     */
    public function getCacheStatus()
    {
        return [
            'provinces' => [
                'has_cache' => Cache::has('wilayah_provinces'),
                'cache_time' => $this->cacheTime,
                'cache_key' => 'wilayah_provinces'
            ],
            'regencies' => [
                'cache_time' => $this->cacheTime,
                'cache_pattern' => 'wilayah_regencies_*'
            ]
        ];
    }
} 