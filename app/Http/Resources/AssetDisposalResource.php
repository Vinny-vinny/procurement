<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AssetDisposalResource extends JsonResource
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
            'id'=> $this->id,
            'opening_date'=> $this->opening_date,
            'deadline_date'=> $this->deadline_date,
            'disposal_no'=> $this->disposal_no,
            'date_opened'=> Carbon::parse($this->opening_date)->format('d-m-Y'),
            'date_closed'=> Carbon::parse($this->deadline_date)->format('d-m-Y'),
            'asset_details' => json_decode($this->asset_details)
        ];
    }
}
