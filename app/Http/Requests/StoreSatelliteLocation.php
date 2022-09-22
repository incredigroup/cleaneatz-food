<?php

namespace App\Http\Requests;

use App\Traits\AuthorizesStorePolicies;
use Illuminate\Foundation\Http\FormRequest;

class StoreSatelliteLocation extends FormRequest
{
    // use AuthorizesStoreFormRequests;

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
        return [
            'store_location_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'fee' => 'required|numeric',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
            'is_approved' => 'boolean',
        ];
    }
}
