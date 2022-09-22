<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUser extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->route('user');

        return [
            'role' =>  'sometimes|required',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'username' => [
                'required',
                'max:255',
                Rule::unique('users')->ignore($user),
            ],
            'email' => 'required|email|max:255',
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('store_location_id', 'required', function ($input) {
            return isset($input->role) && $input->role === 'store';
        });
        $validator->sometimes('password', 'required|string|min:6|confirmed', function () {
            return empty($this->route('user')->id);
        });
    }
}
