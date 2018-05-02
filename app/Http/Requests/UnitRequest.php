<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UnitRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('unit');

        return [
            'quantity' => 'required|numeric|min:1',
            'name' => [
                'required',
                Rule::unique('units', 'name')->ignore($id)
            ]
        ];
    }
}
