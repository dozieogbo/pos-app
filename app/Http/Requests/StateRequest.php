<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('state');

        return [
            'name' => ['required', 'string',
                Rule::unique('states', 'name')->ignore($id)
            ]
        ];
    }
}
