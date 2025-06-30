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
                    'points_weekday' => $taskData['points_weekday'] ?? 1,
                    'points_weekend' => $taskData['points_weekend'] ?? 2,
                    'days' => $taskData['days'] ?? null,
                    'availability_dates' => $taskData['availability_dates'] ?? null,
                    'requires_photo' => $taskData['requires_photo'] ?? false,
                    'replicate' => $taskData['replicate'] ?? false,
                    'media_type' => $taskData['media_type'] ?? null,
                    'options' => $taskData['options'] ?? null,
                    'is_bonus' => $taskData['is_bonus'] ?? false,
                    'max_completions' => $taskData['max_completions'] ?? null,
                    'is_required' => $taskData['is_required'] ?? true,
                    'start_time' => $taskData['start_time'] ?? null,
                    'end_time' => $taskData['end_time'] ?? null,
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

        // Use custom points if provided, otherwise calculate based on weekday
        if (isset($data['points_awarded']) && $data['points_awarded'] !== null) {
            $points = $data['points_awarded'];
        } else {
            $weekday = now()->dayOfWeekIso;
            $points = (in_array($weekday, [6,7]) ? $task->points_weekend : $task->points_weekday);
        }

        // Handle file upload if present
        $mediaUrl = null;
        if (isset($data['media_file']) && $data['media_file']) {
            $file = $data['media_file'];
            $filename = time() . '_' . $userId . '_' . $taskId . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('task-completions', $filename, 'public');
            $mediaUrl = '/storage/' . $path;
        }

        return TaskCompletion::create([
            'challenge_participant_id' => $participant->id,
            'task_id' => $taskId,
            'date' => $date,
            'completed' => filter_var($data['completed'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'media_url' => $mediaUrl,
            'media_type' => $data['media_type'] ?? null,
            'text_proof' => $data['text_proof'] ?? null,
            'checked_options' => $data['checked_options'] ?? null,
            'points_awarded' => $points,
            'status' => $data['status'] ?? 'approved',
        ]);
    }
}
