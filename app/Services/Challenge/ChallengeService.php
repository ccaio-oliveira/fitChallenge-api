<?php

namespace App\Services\Challenge;

use App\Models\Challenge;
use App\Models\ChallengeParticipant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ChallengeService
{
    public function createChallenge(array $data, int $adminId): Challenge
    {
        return DB::transaction(function () use ($data, $adminId) {
            $challenge = Challenge::create([
                'admin_id' => $adminId,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'invite_token' => Str::uuid(),
                'status' => 'active'
            ]);

            foreach ($data['tasks'] as $taskData) {
                $challenge->tasks()->create([
                    'name' => $taskData['name'],
                    'description' => $taskData['description'] ?? null,
                    'days' => $taskData['days'] ?? null,
                    'requires_photo' => $taskData['requires_photo'] ?? false,
                    'points_weekday' => $taskData['points_weekday'] ?? 1,
                    'points_weekend' => $taskData['points_weekend'] ?? 2,
                    'replicate' => $taskData['replicate'] ?? false,
                    'availability_dates' => $taskData['availability_dates'] ?? null
                ]);
            }

            ChallengeParticipant::create([
                'challenge_id' => $challenge->id,
                'user_id' => $adminId,
                'joined_at' => now(),
            ]);

            return $challenge;
        });
    }
}