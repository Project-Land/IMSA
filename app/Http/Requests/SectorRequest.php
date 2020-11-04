<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectorRequest extends FormRequest
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
            'name' => 'required|max:190'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Unesite naziv sektora',
            'name.max' => 'Naziv sektora ne sme biti duži od 190 karaktera'
        ];
    }
}
