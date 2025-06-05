<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'key',
        'content',
        'type'
    ];

    protected $casts = [
        'content' => 'array'
    ];
} 