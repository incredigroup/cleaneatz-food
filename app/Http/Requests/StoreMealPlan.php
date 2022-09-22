<?php

namespace App\Http\Requests;

use App\Traits\ParsesDates;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMealPlan extends FormRequest
{
    use ParsesDates;

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
            'name' => 'required|string|max:255',
            'available_on' => 'required|string|max:255',
            'expires_on' => 'required|string|max:255',
            'meal_id' => 'present',
        ];
    }

    public function validated()
    {
        $input = parent::validated();

        $input['available_on'] = empty($input['available_on']) ?
            null : $this->fromLocalDateTime($input['available_on']);

        $input['expires_on'] = empty($input['expires_on']) ?
            null : $this->fromLocalDateTime($input['expires_on']);

        return $input;
    }
}
