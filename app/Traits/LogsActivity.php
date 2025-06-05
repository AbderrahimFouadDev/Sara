<?php

namespace App\Traits;

use App\Models\Activity;

trait LogsActivity
{
    /**
     * Log an activity
     *
     * @param string $type
     * @param string $description
     * @param int|null $userId
     * @return void
     */
    protected function logActivity($type, $description, $userId = null)
    {
        if (!$userId && auth()->check()) {
            $userId = auth()->id();
        }

        Activity::create([
            'user_id' => $userId,
            'type' => $type,
            'description' => $description,
        ]);
    }
} 