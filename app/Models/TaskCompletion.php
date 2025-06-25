<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_participant_id',
        'task_id',
        'date',
        'completed',
        'photo_url',
        'points_awarded',
        'media_url',
        'media_type',
        'text_proof',
        'checked_options',
        'status'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'checked_options' => 'array',
    ];

    public function participant()
    {
        return $this->belongsTo(ChallengeParticipant::class, 'challenge_participant_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
