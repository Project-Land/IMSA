<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRiskManagementPlanRequest extends FormRequest
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
            'cause' => 'required',
            'risk_lowering_measure' => 'required',
            'responsibility' => 'required',
            'deadline' => 'required|after:yesterday'
        ];
    }

    public function messages()
    {
        return [
            'cause.required' => 'Unesite uzrok',
            'risk_lowering_measure.required' => 'Unesite meru za smanjenje rizika',
            'responsibility.required' => 'Unesite odgovornost',
            'deadline.required' => 'Unesite rok za realizaciju',
            'deadline.after' => 'Unesite budući datum'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'deadline' => $this->deadline != null ? date('Y-m-d', strtotime($this->deadline)) : null,
        ]);
    }
}
