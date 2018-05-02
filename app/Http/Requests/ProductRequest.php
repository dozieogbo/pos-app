<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ProductRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('product');

        return [
            'name' => 'required',
            'image' => $id ? 'image' : 'required|image',
            'description' => 'required',
            'price' => 'required',
            'size' => 'required',
            'category' => 'required|exists:categories,id',
            'barcode' => ['required', 'string',
                Rule::unique('categories', 'name')->ignore($id)
            ],
        ];
    }
}
