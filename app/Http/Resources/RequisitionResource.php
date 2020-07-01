<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class RequisitionResource extends JsonResource
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
            'requisition_type_id' => $this->requisition_type_id,
            'req_date' => $this->req_date,
            'date_requested' => Carbon::parse($this->req_date)->format('d/m/Y'),
            'department_id' => $this->department_id,
            'priority_id' => $this->priority_id,
            'project_id' => $this->project_id,
            'project' => $this->project->code,
            'requisition_by' => $this->requisition_by,
            'person_requested' => $this->user->name,
            'description' => $this->description,
            'req_no' => $this->req_no,         
            'item_stock' => json_decode($this->item_stock),
            'item_asset' => json_decode($this->item_asset),
            'item_service' => json_decode($this->item_service)

        ];
    }
}
