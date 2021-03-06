<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class EnvironmentalAspectsRequest extends FormRequest
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
            'process' => 'required',
            'waste' => 'required',
            'aspect' => 'required',
            'influence' => 'required',
            'waste_type' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'process.required' => __('Polje je obavezno'),
            'waste.required' => __('Polje je obavezno'),
            'aspect.required' => __('Polje je obavezno'),
            'influence.required' => __('Polje je obavezno'),
            'waste_type' => __('Izaberite karakter otpada')
        ];
    }

    protected function prepareForValidation(): void
    {
        $total = $this->probability_of_discovery + 2*($this->probability_of_appearance + $this->severity_of_consequences);

        $this->merge([
            'user_id' => Auth::user()->id,
            'team_id' => Auth::user()->current_team_id,
            'standard_id' => session('standard'),
            'estimated_impact' => $total
        ]);
    }
}
