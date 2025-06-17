<?php

namespace App\Http\Controllers;

use App\Http\Requests\Challenge\StoreChallengeRequest;
use App\Http\Resources\ChallengeResource;
use ChallengeService;
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

    public function store(StoreChallengeRequest $request): JsonResponse
    {
        $challenge = $this->challengeService->createChallenge($request->validated(), Auth::id());
        return response()->json(new ChallengeResource($challenge->load('tasks', 'participants')), 201);
    }
}
