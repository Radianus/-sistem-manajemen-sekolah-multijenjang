<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Untuk bekerja dengan tanggal

class CalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'event_type',
        'target_roles',
        'created_by_user_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime', // Cast ke Carbon untuk kemudahan format
        'end_time' => 'datetime',   // Cast ke Carbon untuk kemudahan format
    ];

    /**
     * Get the user who created the calendar event.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Scope a query to only include events for a specific month/year.
     */
    public function scopeForMonthAndYear($query, $month, $year)
    {
        return $query->whereYear('start_date', $year)
            ->whereMonth('start_date', $month);
    }

    /**
     * Scope a query to only include events active within a given date range.
     */
    public function scopeActiveBetween($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->where('start_date', '<=', $endDate)
                ->where(function ($qq) use ($startDate) {
                    $qq->whereNull('end_date')
                        ->orWhere('end_date', '>=', $startDate);
                });
        });
    }

    /**
     * Scope a query to only include events targeted to specific roles.
     */
    public function scopeTargetedTo($query, $roles)
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        return $query->where(function ($q) use ($roles) {
            $q->where('target_roles', 'all'); // Always show to 'all'
            foreach ($roles as $role) {
                $q->orWhere('target_roles', 'like', '%' . $role . '%'); // Match if role is in target_roles string
            }
        });
    }
}
