<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeasuringEquipmentsRequest extends FormRequest
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
        if($this->isMethod('post')){
            return $this->createRules();
        }
        elseif($this->isMethod('put')){
            return $this->updateRules();
        }
    }
    public function createRules()
    {
        return [
            'label' => 'required|max:190',
            'name' => 'required|max:190',
            'next_calibration_date' => 'required|after:yesterday',
        ];
    }

    public function updateRules()
    {
        return [
            'label' => 'required|max:190',
            'name' => 'required|max:190',
            'next_calibration_date' => 'required|after:yesterday',
        ];
    }

    public function messages()
    {
        return [
            'label.required' => 'Unesite oznaku',
            'label.max' => 'Polje može sadržati najviše 190 karaktera',
            'name.required' => 'Unesite naziv',
            'name.max' => 'Polje može sadržati najviše 190 karaktera',
            'next_calibration_date.required' => 'Unesite datum narednog etaloniranja/bandažiranja',
            'next_calibration_date.after' => 'Unesite budući datum'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'standard_id' => session('standard'),
            'team_id' => \Auth::user()->current_team_id,
            'user_id' => \Auth::user()->id,
        ]);
    }

}
