<?php

namespace App\Http\Requests;

class PurchaseRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer' => 'required|string|exists:customers,id',
            'pay_type' => 'required',
            'branch' => 'required|numeric',
            'amount_paid' => 'required',
            'details' => 'array|required',
            'details.*.price' => 'required',
            'details.*.quantity' => 'required|numeric|min:1',
            'details.*.product_id' => 'required|exists:products,id'
        ];
    }
}
