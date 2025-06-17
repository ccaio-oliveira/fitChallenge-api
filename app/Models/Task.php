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
        'availability_dates'
    ];

    protected $casts = [
        'days' => 'array',
        'requires_photo' => 'boolean',
        'replicate' => 'boolean',
        'availability_dates' => 'array'
    ];

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
}
