<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class BranchRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('branch');

        return [
            'address' => 'required',
            'state' => 'required',
            'code' => ['required', 'string',
                Rule::unique('states', 'name')->ignore($id)
            ]
        ];
    }
}
