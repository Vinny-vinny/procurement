<?php

namespace App\Http\Resources;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
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
            'po_date' => $this->po_date,
            'date_requested' => Carbon::parse($this->po_date)->format('d/m/Y'),
            'po_no' => $this->po_no,
            'quotation_id' => $this->quotation_id,
            'item_type' => $this->item_type,
            'item_stock' => json_decode($this->item_stock),
            'item_asset' => json_decode($this->item_asset),
            'po_description' => $this->po_description,
            'supplier_id' => $this->supplier_id
        ];
    }
}
