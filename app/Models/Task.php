<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'name',
        'description',
        'days',
        'requires_photo',
        'points_weekday',
        'points_weekend',
        'replicate',
        'availability_dates',
        'media_type',
        'options',
        'is_bonus',
        'max_completions',
        'is_required',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'days' => 'array',
        'availability_dates' => 'array',
        'options' => 'array',
        'is_bonus' => 'boolean',
        'is_required' => 'boolean'
    ];

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
}
