<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class DepartmentBudgetResource extends JsonResource
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
            'department_id' => $this->department_id,
            'department' => $this->department->name,
            'total_amount' => $this->total_amount,
            'total' => $this->total_amount,
            'begins_on' => $this->begins_on,
            'ends_on' => $this->ends_on,
            'start_date_fr' => Carbon::parse($this->begins_on)->format('d/m/Y'),
            'end_date_fr' => Carbon::parse($this->ends_on)->format('d/m/Y'),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'renewal_type' => $this->renewal_type,
            'frequency_type' => $this->frequency_type,
            'period' => $this->period,
            'item_type' => $this->item_type,
            'item_stock' => json_decode($this->item_stock),
            'item_asset' => json_decode($this->item_asset),
        ];
    }
}
