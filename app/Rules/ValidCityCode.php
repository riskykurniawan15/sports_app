<?php

namespace App\Rules;

use App\Services\WilayahService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCityCode implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        // Extract province code from city code (before the dot)
        $provinceCode = explode('.', $value)[0] ?? '';
        
        // Get regencies for that specific province
        $wilayahService = app(WilayahService::class);
        $regencies = $wilayahService->getRegencies($provinceCode);
        
        if (!$regencies) {
            $fail('Unable to validate city code. Please try again later.');
            return;
        }

        // Check if the city code exists in the regencies
        $cityExists = collect($regencies)->contains('code', $value);

        if (!$cityExists) {
            $fail('The selected city code is invalid.');
        }
    }
}
