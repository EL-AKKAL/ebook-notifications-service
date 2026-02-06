<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'user_email' => $this->user_email,
            'title' => $this->title,
            'message' => $this->message,
            'read_at' => $this->read_at,
            'created_at' => $this->created_at,
            'is_read' => $this->is_read,
            'type_object' => $this->type_object
        ];
    }
}
