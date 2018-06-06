<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
           'name' => $this->name,
           'staff_id' => $this->staff_id,
            'gender' => $this->gender,
            'branch' => $this->branchDetail ? $this->branchDetail->branch : null,
            'roles' => $this->roles->map(function ($item){ return $item->name; })
        ];
    }
}
