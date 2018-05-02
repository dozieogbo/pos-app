<?php

namespace App\Http\Requests;

class InventoryRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'branch' => 'required|exists:branches,id',
            'quantity' => 'numeric|quantity',
            'supplier' => 'sometimes|numeric|exists:suppliers,id'
        ];
    }
}
