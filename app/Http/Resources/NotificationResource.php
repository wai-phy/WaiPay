<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'title' => Str::limit($this->data['title'],40),
            'message' => Str::limit($this->data['message'],100),
            'date_time' => Carbon::parse($this->created_at)->format('Y-m-d H:s:i'),
            'read_at' => !is_null($this->read_at) ? 1 : 0,
        ];
    }
}
