<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

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
            'measure' => 'required',
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
            'measure.required' => 'Unesite meru za otklanjanje neusaglašenosti',
        ];
    }

    protected function prepareForValidation(): void
    {
        if($this->isMethod('post')){
            $c = \App\Models\CorrectiveMeasure::whereYear('created_at', '=', Carbon::now()->year)
            ->where([
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->count();

            $last = \App\Models\CorrectiveMeasure::latest()->first();

            if(!$last){
                $counter = 1;
            } else{
                if($last->id == 1){
                    $counter = 2;
                } else{
                    $counter = $last->id + 1;
                }
            };

            $this->merge([
                'name' => "QMS KKM ".Carbon::now()->year." / ".$counter,
            ]);
        }

        $this->merge([
            'user_id' => Auth::user()->id,
            'team_id' => Auth::user()->current_team_id,
            'noncompliance_cause_date' => Carbon::now(),
            'measure_date' => Carbon::now(),
            'measure_approval_reason' => $this->measure_approval_reason != '' ? $this->measure_approval_reason : null,
            'measure_effective' => $this->measure_effective != null || $this->measure_status != 0 ? $this->measure_effective : null,
            'measure_approval_date' => $this->measure_approval == '1' ? Carbon::now() : null
        ]);
    }
}
