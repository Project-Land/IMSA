<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class CorrectiveMeasuresRequest extends FormRequest
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
            'standard_id' => 'required',
            'sector_id' => 'required',
            'noncompliance_source' => 'required',
            'noncompliance_description' => 'required',
            'noncompliance_cause' => 'required',
            'measure' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'standard_id.required' => 'Izaberite standard',
            'sector_id.required' => 'Izaberite organizacionu celinu',
            'noncompliance_source.required' => 'Unesite izvor informacije o neusaglašenosti',
            'noncompliance_description.required' => 'Unesite opis neusaglašenosti',
            'noncompliance_cause.required' => 'Unesite uzrok neusaglašenosti',
            'measure.required' => 'Unesite meru za otklanjanje neusaglašenosti'
        ];
    }

    protected function prepareForValidation(): void
    {
        $counter = \App\Models\CorrectiveMeasure::whereYear('created_at', '=', Carbon::now()->year)
                    ->where([
                        ['standard_id', session('standard')],
                        ['team_id', \Auth::user()->current_team_id]
                    ])
                    ->count() + 1;
        
        $this->merge([
            'user_id' => \Auth::user()->id,
            'team_id' => \Auth::user()->current_team_id,
            'name' => "KKM ".Carbon::now()->year." / ".$counter,
            'noncompliance_cause_date' => Carbon::now(),
            'measure_date' => Carbon::now(),
            'measure_approval_reason' => $this->measure_approval_reason != '' ? $this->measure_approval_reason : null,
            'measure_effective' => $this->measure_effective != null || $this->measure_status != 0 ? $this->measure_effective : null,
            'measure_approval_date' => $this->measure_approval == '1' ? Carbon::now() : null
        ]);
    }
}
