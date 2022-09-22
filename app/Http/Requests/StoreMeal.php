<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeal extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'description' => 'string|max:2048',
            'group_desc' => 'string|max:2048',
            'is_breakfast' => 'boolean',
            'price_override' => 'numeric',
            'calories' => 'numeric',
            'fat' => 'numeric',
            'protein' => 'numeric',
            'carbs' => 'numeric',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function validated()
    {
        $input = parent::validated();

        if (isset($input['image'])) {
            // give the image a unique name
            $imageName = time() . '.' . $input['image']->getClientOriginalExtension();
            $input['image_url'] = $imageName;

            // move the image to the public path
            $input['image']->move(public_path('img/meals'), $imageName);
        }

        return $input;
    }
}
