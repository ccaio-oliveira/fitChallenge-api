<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ChallengeResource extends JsonResource
{
    protected $currentUserId;

    public function __construct($resource, $currentUserId = null)
    {
        parent::__construct($resource);
        $this->currentUserId = $currentUserId ?? Auth::id();
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'description'  => $this->description,
            'start_date'   => $this->start_date,
            'end_date'     => $this->end_date,
            'admin_id'     => $this->admin_id,
            'current_user_id' => $this->currentUserId,
            'invite_token' => $this->invite_token,
            'tasks'        => TaskResource::collection($this->whenLoaded('tasks')),
            'participants' => $this->whenLoaded('participants', fn () => $this->participants->count()),
            'status'       => $this->status,
            'created_at'   => $this->created_at,
        ];
    }
}
