<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'description',
    ];

    /**
     * Get the user that owns the activity.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the formatted time difference for humans.
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get the icon class based on activity type.
     */
    public function getIconClassAttribute()
    {
        return match($this->type) {
            'new_user' => 'fa-user-plus',
            'login' => 'fa-sign-in-alt',
            'logout' => 'fa-sign-out-alt',
            'status_change' => 'fa-user-clock',
            'profile_update' => 'fa-user-edit',
            default => 'fa-info-circle',
        };
    }
} 