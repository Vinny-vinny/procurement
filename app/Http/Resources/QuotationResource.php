<?php

namespace App\Http\Resources;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationResource extends JsonResource
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
            'quote_no' => $this->quote_no,
            'quote_date' => $this->quote_date,
            'quotation_date' => Carbon::parse($this->quote_date)->format('d/m/Y'),
            'description' => $this->description,
            'supplier_id' => $this->supplier_id,
            'supplier' => $this->supplier->name,
            'enquiry_id' => $this->enquiry_id,
        ];
    }
}
