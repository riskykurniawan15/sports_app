<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'desc'
    ];

    /**
     * Scope to search positions by name or code
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Scope to filter by position category
     */
    public function scopeByCategory($query, $category)
    {
        if ($category) {
            switch (strtolower($category)) {
                case 'goalkeeper':
                case 'gk':
                    return $query->where('code', 'GK');
                case 'defender':
                case 'def':
                    return $query->whereIn('code', ['CB', 'LCB', 'RCB', 'LB', 'RB', 'LWB', 'RWB', 'CWB', 'IFB', 'SW']);
                case 'midfielder':
                case 'mid':
                    return $query->whereIn('code', ['CM', 'CDM', 'DM', 'B2B', 'CAM', 'AM', 'LM', 'RM', 'LWM', 'RWM', 'DLP', 'RPM', 'AP', 'TQ', 'HB']);
                case 'forward':
                case 'fwd':
                    return $query->whereIn('code', ['CF', 'ST', 'SS', 'LW', 'RW', 'IW', 'IF', 'WF', 'F9', 'TM', 'PO', 'PF']);
                default:
                    return $query;
            }
        }
        return $query;
    }
}
