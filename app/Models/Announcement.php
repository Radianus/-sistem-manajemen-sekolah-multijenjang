<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'published_at',
        'expires_at',
        'created_by_user_id',
        'target_roles',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user who created the announcement.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Scope a query to only include active announcements.
     * Announcements are active if published_at is past or null, and expires_at is future or null.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('published_at')
                ->orWhere('published_at', '<=', Carbon::now());
        })->where(function ($q) {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>=', Carbon::now());
        });
    }

    /**
     * Scope a query to only include announcements targeted to specific roles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array|string  $roles  A single role or an array of roles to filter by.
     * @return \Illuminate\Database\Eloquent\Builder
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
