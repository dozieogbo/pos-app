<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class PaymentTypeRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('payment_type');

        return [
            'name' => ['required', 'string',
                Rule::unique('payment_types', 'name')->ignore($id)
            ]
        ];
    }
}
