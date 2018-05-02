<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class FlagRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('flag');

        return [
            'value' => 'required',
            'key' => ['required', 'string',
                Rule::unique('settings', 'id')->ignore($id)
            ]
        ];
    }
}
