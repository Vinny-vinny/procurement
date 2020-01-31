<?php

namespace App\Http\Resources;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EnquiryResource extends JsonResource
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
            'item_type' => $this->item_type,
            'item_stock' => json_decode($this->item_stock),
            'item_asset' => json_decode($this->item_asset),
            'supplier_id' => json_decode($this->supplier_id),
            'item_service' => json_decode($this->item_service),
            'enquiry_no' => $this->enquiry_no,
            'enquiry_date' => $this->enquiry_date,
            'date_requested' => Carbon::parse($this->enquiry_date)->format('d/m/Y'),
            'title' => $this->title,
            'description' => $this->description,
            'req_date_from' => $this->req_date_from,
            'req_date_to' => $this->req_date_to
        ];
    }
}
