<?php

namespace App\Http\Controllers;

use App\Http\Requests\Challenge\StoreChallengeRequest;
use App\Http\Requests\CompleteTaskRequest;
use App\Http\Resources\ChallengeResource;
use App\Models\Challenge;
use App\Services\Challenge\ChallengeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChallengeController extends Controller
{
    protected ChallengeService $challengeService;

    public function __construct(ChallengeService $challengeService)
    {
        $this->challengeService = $challengeService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $challenges = $this->challengeService->getUserChallenges($user->id);
        return ChallengeResource::collection($challenges);
    }

    public function store(StoreChallengeRequest $request): JsonResponse
    {
        $challenge = $this->challengeService->createChallenge($request->validated(), Auth::id());
        return response()->json(new ChallengeResource($challenge->load('tasks', 'participants')), 201);
    }

    public function show(Request $request, $id)
    {
        $challenge = $this->challengeService->getChallengeDetail($id, $request->user()->id);
        if (!$challenge) {
            return response()->json(['message' => 'Desafio não encontrado'], 404);
        }
        return (new ChallengeResource($challenge, $request->user()->id));
    }

    public function completeTask(CompleteTaskRequest $request, $challengeId, $taskId)
    {
        try {
            $data = $request->validated();
            
            // Add the file to the data if present
            if ($request->hasFile('media_file')) {
                $data['media_file'] = $request->file('media_file');
            }
            
            $completion = $this->challengeService->completeTask($data, $challengeId, $taskId, $request->user()->id);
            return response()->json(['success' => true, 'completion' => $completion], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
