<?php

namespace App\Http\Requests;

use App\Models\PromoCode;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePromoCode extends FormRequest
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
        $promoCode = $this->route('promo_code');
        return [
            'store_location_id' => 'nullable',
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('promo_codes')
                    ->ignore($promoCode)
                    ->where(function ($query) {
                        return $query
                            ->where('store_location_id', $this->get('store_location_id'))
                            ->whereNull('deleted_at');
                    }),
            ],
            'discount_amount' => [
                'nullable',
                'numeric',
                'required_without:discount_percent',
                function ($attribute, $value, $fail) {
                    if (
                        !empty(
                            $this->get('discount_percent') && !empty($this->get('discount_amount'))
                        )
                    ) {
                        return $fail('Cannot set both a Discount Amount and Discount Percent');
                    }
                },
            ],
            'min_meals_required' => 'numeric|required',
            'match_type' => 'string|required',
            'discount_percent' => 'nullable|numeric|required_without:discount_amount',
            'start_on' => 'nullable|date',
            'end_on' => 'nullable|date',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'min_meals_required' => $this->min_meals_required ?? '0',
            'match_type' => $this->match_type ?? PromoCode::MATCH_TYPE_EQUALS,
        ]);
    }

    public function validated()
    {
        $input = parent::validated();

        $input['start_on'] = empty($input['start_on'])
            ? null
            : Carbon::createFromFormat('m/d/Y', $input['start_on']);

        $input['end_on'] = empty($input['end_on'])
            ? null
            : Carbon::createFromFormat('m/d/Y', $input['end_on']);

        $input['discount_amount'] = $input['discount_amount'] ?: 0;

        return $input;
    }
}
