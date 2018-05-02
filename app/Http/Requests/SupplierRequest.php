<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class SupplierRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('supplier');

        return [
            'name' => 'required|string',
            'code' => [
                'required',
                Rule::unique('suppliers', 'code')->ignore($id)
            ]
        ];
    }
}
