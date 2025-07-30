<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Rules\ValidCityCode;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'logo',
        'established_year',
        'address',
        'city'
    ];

    protected $casts = [
        'established_year' => 'integer',
    ];

    protected $hidden = [
        'deleted_at'
    ];

    /**
     * Get validation rules for creating a team
     */
    public static function getCreateRules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'logo' => 'nullable|string|max:250|url',
            'established_year' => 'required|integer|min:1800|max:' . (date('Y') + 1),
            'address' => 'required|string|max:1000',
            'city' => ['required', 'string', 'max:10', new ValidCityCode]
        ];
    }

    /**
     * Get validation rules for updating a team
     */
    public static function getUpdateRules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:100',
            'logo' => 'sometimes|nullable|string|max:250|url',
            'established_year' => 'sometimes|required|integer|min:1800|max:' . (date('Y') + 1),
            'address' => 'sometimes|required|string|max:1000',
            'city' => ['sometimes', 'required', 'string', 'max:10', new ValidCityCode]
        ];
    }

    /**
     * Scope to search teams by name
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('name', 'like', "%{$search}%");
        }
        return $query;
    }

    /**
     * Scope to filter by city
     */
    public function scopeByCity($query, $cityCode)
    {
        if ($cityCode) {
            return $query->where('city', $cityCode);
        }
        return $query;
    }

    /**
     * Get city name from wilayah service
     */
    public function getCityNameAttribute()
    {
        if (!$this->city) {
            return null;
        }

        try {
            $wilayahService = app(\App\Services\WilayahService::class);
            
            // Extract province code from city code (before the dot)
            $provinceCode = explode('.', $this->city)[0] ?? '';
            
            // Get regencies for that province
            $regencies = $wilayahService->getRegencies($provinceCode);
            
            if ($regencies) {
                $regency = collect($regencies)->firstWhere('code', $this->city);
                return $regency ? $regency['name'] : null;
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get province name from wilayah service
     */
    public function getProvinceNameAttribute()
    {
        if (!$this->city) {
            return null;
        }

        try {
            $wilayahService = app(\App\Services\WilayahService::class);
            
            // Extract province code from city code (before the dot)
            $provinceCode = explode('.', $this->city)[0] ?? '';
            
            // Get provinces
            $provinces = $wilayahService->getProvinces();
            
            if ($provinces) {
                $province = collect($provinces)->firstWhere('code', $provinceCode);
                return $province ? $province['name'] : null;
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
