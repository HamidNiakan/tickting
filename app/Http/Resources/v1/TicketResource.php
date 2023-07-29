<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\TicketReplayResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
			'id' => $this->id,
			'user_id' => $this->user_id,
			'title' => $this->title,
			'message' => $this->message,
			'priority' => $this->priority->value,
			'status' => $this->status->value,
			'is_read' => $this->is_read,
			'user' => $this->whenLoaded('user',UserResource::make($this->user)),
			'ticketReplies' => $this->whenLoaded('ticketReplies',TicketReplayResource::collection($this->ticketReplies))
		];
    }
}
