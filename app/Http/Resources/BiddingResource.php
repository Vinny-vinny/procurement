<?php

namespace App\Http\Resources;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BiddingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'bid_date' => Carbon::parse($this->bid_date)->format('d-m-Y'),
            'bid_no' => $this->bid_no,
            'user' => $this->user->name,
            'user_id' => $this->user_id,
            'disposal_no' => $this->disposal->disposal_no,
            'disposal_id' => $this->disposal_id,
            'asset_details' => json_decode($this->asset_details),
            'payment_mode' => $this->payment_mode->name,
            
        ];
    }
}
