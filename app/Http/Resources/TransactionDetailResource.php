<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'trx_id' => $this->trx_id,
            'ref_no' => $this->ref_no,
            'type' => $this->type,
            'amount' => $this->amount,
            'date_time' => Carbon::parse($this->created_at)->format('Y-m-d H:s:i'),
            'source' => $this->source? $this->source->name : '',
            'description' => $this->description,
        ];
    }
}
