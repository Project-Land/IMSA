<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
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
            'next_calibration_date' => 'after:yesterday|nullable',
            'last_calibration_date' => 'before:tomorrow|nullable',
        ];
    }

    public function updateRules()
    {
        return [
            'label' => 'required|max:190',
            'name' => 'required|max:190',
            'next_calibration_date' => 'after:yesterday|nullable',
            'last_calibration_date' => 'before:tomorrow|nullable',
        ];
    }

    public function messages()
    {
        return [
            'label.required' => __('Polje je obavezno'),
            'label.max' => __('Polje može sadržati najviše 190 karaktera'),
            'name.required' => __('Polje je obavezno'),
            'name.max' => __('Polje može sadržati najviše 190 karaktera'),
            'next_calibration_date.required' => __('Unesite datum narednog etaloniranja/baždarenja'),
            'next_calibration_date.after' => __('Unesite budući datum'),
            'last_calibration_date.before' => __('Nije moguće uneti budući datum')
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'standard_id' => session('standard'),
            'team_id' => Auth::user()->current_team_id,
            'user_id' => Auth::user()->id,
            'next_calibration_date'=>$this->next_calibration_date != null ? date('Y-m-d', strtotime($this->next_calibration_date)):null,
            'last_calibration_date' => $this->last_calibration_date != null ? date('Y-m-d', strtotime($this->last_calibration_date)):null,
        ]);
    }

}
