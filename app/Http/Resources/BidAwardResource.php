<?php

namespace App\Http\Resources;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BidAwardResource extends JsonResource
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
            'asset_details' => json_decode($this->asset_details),
            'ref_no' => $this->ref_no,
            'disposal_no' => $this->disposal->disposal_no,
            'created' => Carbon::parse($this->created_at)->format('d-m-Y')
        ];
    }
}
