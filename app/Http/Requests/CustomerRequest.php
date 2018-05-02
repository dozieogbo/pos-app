<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CustomerRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('customer');

        return [
            'phone' => ['required',
                Rule::unique('customers', 'phone')->ignore($id)],
            'name' => 'required'
        ];
    }
}
