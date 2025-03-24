<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PresenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'creator' => $this->createdBy?->name,
            'store' => $this->store?->nickname,
            'shift_name' => $this->shiftStore?->name,
            'shift_start_time' => $this->shiftStore?->shift_start_time,
            'shift_end_time' => $this->shiftStore?->shift_end_time,
            'shift_duration' => $this->shiftStore?->duration,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}