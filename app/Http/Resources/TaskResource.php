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
            'id'                => $this->id,
            'challenge_id'      => $this->challenge_id,
            'name'              => $this->name,
            'description'       => $this->description,
            'days'              => $this->days,
            'requires_photo'    => $this->requires_photo,
            'points_weekday'    => $this->points_weekday,
            'points_weekend'    => $this->points_weekend,
            'replicate'         => $this->replicate,
            'availability_dates'=> $this->availability_dates,
            'created_at'        => $this->created_at,
        ];
    }
}
