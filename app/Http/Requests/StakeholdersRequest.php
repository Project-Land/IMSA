<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StakeholdersRequest extends FormRequest
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
            'name' => 'required|max:190',
            'expectation' => 'required',
            'response' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('Unesite naziv / ime zainteresovane strane'),
            'name.max' => __('Naziv može sadržati maksimalno 190 karaktera'),
            'expectation.required' => __('Polje je obavezno'),
            'response.required' => __('Polje je obavezno')
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'standard_id' => session('standard'),
            'team_id' => Auth::user()->current_team_id,
            'user_id' => Auth::user()->id
        ]);
    }
}
