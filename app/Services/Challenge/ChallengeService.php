<?php

namespace App\Services\Challenge;

use App\Models\Challenge;
use App\Models\ChallengeParticipant;
use App\Models\Task;
use App\Models\TaskCompletion;
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

    public function getUserChallenges(int $userId)
    {
        return Challenge::where('admin_id', $userId)
        ->orWhereHas('participants', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->with(['tasks', 'participants.user'])
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function getChallengeDetail(int $challengeId, int $userId)
    {
        return Challenge::with(['tasks', 'participants.user'])
        ->where('id', $challengeId)
        ->where(function ($q) use ($userId) {
            $q->where('admin_id', $userId)
            ->orWhereHas('participants', function ($q2) use ($userId) {
                $q2->where('user_id', $userId);
            });
        })
        ->first();
    }

    public function completeTask(array $data, int $challengeId, int $taskId, int $userId)
    {
        $participant = ChallengeParticipant::where('challenge_id', $challengeId)
        ->where('user_id', $userId)
        ->first();

        if (!$participant) {
            throw new \Exception('Usuário não faz parte deste desafio!.');
        }

        $date = $data['date'] ?? now()->toDateString();
        $exists = TaskCompletion::where([
            'challenge_participant_id' => $participant->id,
            'task_id' => $taskId,
            'date' => $date,
        ])->exists();

        if ($exists) {
            throw new \Exception('Tarefa já registrada para hoje.');
        }

        $task = Task::findOrFail($taskId);
        $weekday = now()->dayOfWeekIso;
        $points = (in_array($weekday, [6,7]) ? $task->points_weekend : $task->points_weekday);

        return TaskCompletion::create([
            'challenge_participant_id' => $participant->id,
            'task_id' => $taskId,
            'date' => $date,
            'completed' => true,
            'photo_url' => $data['photo_url'] ?? null,
            'media_url' => $data['media_url'] ?? null,
            'media_type' => $data['media_type'] ?? null,
            'text_proof' => $data['text_proof'] ?? null,
            'checked_options' => $data['checked_options'] ?? null,
            'points_awarded' => $points,
            'status' => $data['status'] ?? 'approved',
        ]);
    }
}
