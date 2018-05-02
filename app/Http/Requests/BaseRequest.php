<?php

namespace App\Http\Requests;

use App\Helper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public abstract function rules();

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Helper::invalid($validator));
    }
}
