<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RiskManagementRequest extends FormRequest
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
            'description' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'Unesite opis'
        ];
    }

    protected function prepareForValidation(): void
    {
        $standardId = session('standard');
        $total = $this->probability * $this->frequency;
        $count = \App\Models\RiskManagement::whereNotNull('measure')->count() + 1;

        if($this->acceptable < $total){
            if($this->isMethod('put')){
                $id = $this->route('risk_management');
                $risk = \App\Models\RiskManagement::find($id);
                if($risk->measure == null){
                    $this->merge([
                        'measure' => 'Plan za postupanje sa rizikom '.$count,
                        'measure_created_at' => \Carbon\Carbon::now()->toDateTimeString()
                    ]);
                }
            }
            else{
                $this->merge([
                    'measure' => 'Plan za postupanje sa rizikom '.$count,
                    'measure_created_at' => \Carbon\Carbon::now()->toDateTimeString()
                ]);
            }
        }

        $this->merge([
            'standard_id' => $standardId,
            'user_id' => \Auth::user()->id,
            'team_id' => \Auth::user()->current_team_id,
            'total' => $total
        ]);
    }
}
