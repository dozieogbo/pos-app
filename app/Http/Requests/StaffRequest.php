<?php

namespace App\Http\Requests;

use App\Helper;
use Illuminate\Validation\Rule;

class StaffRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('staff');

        $rules = [
            'name' => 'required|string',
            'gender' => 'required|string|in:male,female',
            'role' => [
                'required',
                Rule::in(Helper::getStaffRoles())
            ],
            'staff_id' => [
                'required',
                Rule::unique('users', 'staff_id')->ignore($id)
            ]
        ];

        if(!$id){
            $rules['branch'] = 'required|numeric|exists:branches,id';
        }

        return $rules;
    }
}
