<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'challenge_id'       => $this->challenge_id,
            'name'               => $this->name,
            'description'        => $this->description,
            'days'               => $this->days,
            'requires_photo'     => $this->requires_photo,
            'points_weekday'     => $this->points_weekday,
            'points_weekend'     => $this->points_weekend,
            'replicate'          => $this->replicate,
            'availability_dates' => $this->availability_dates,
            'media_type'         => $this->media_type,
            'options'            => $this->options,
            'is_bonus'           => $this->is_bonus,
            'max_completions'    => $this->max_completions,
            'is_required'        => $this->is_required,
            'start_time'         => $this->start_time,
            'end_time'           => $this->end_time,
            'created_at'         => $this->created_at,
        ];
    }
}
