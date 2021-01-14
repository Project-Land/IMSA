<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class AccidentRequest extends FormRequest
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
            'name' => 'required|max:190',
            'injury_type'=>'required|max:190',
            'jobs_and_tasks_he_performs'=>'required|max:500',
            'injury_datetime'=>'required|before:tomorrow',
            'injury_cause'=>'required|max:500',
            'injury_description'=>'required|max:500',
            'error'=>'required|max:500',
            'order_from'=>'required|max:190',
            'dangers_and_risks'=>'required|max:500',
            'protective_equipment'=>'required|max:500',
            'high_risk_jobs'=>'required|max:500',
            'job_requirements'=>'required|max:500',
            'supervisor'=>'required|max:190',
            'witness'=>'nullable|max:500',
            'injury_report_datetime'=>'required|before:tomorrow',
            'comment'=>'nullable|max:500',
            
        ];
    }

    public function updateRules()
    {
        return [
            'name' => 'required|max:190',
            'injury_type'=>'required|max:190',
            'jobs_and_tasks_he_performs'=>'required|max:500',
            'injury_datetime'=>'required|before:tomorrow',
            'injury_cause'=>'required|max:500',
            'injury_description'=>'required|max:500',
            'error'=>'required|max:500',
            'order_from'=>'required|max:190',
            'dangers_and_risks'=>'required|max:500',
            'protective_equipment'=>'required|max:500',
            'high_risk_jobs'=>'required|max:500',
            'job_requirements'=>'required|max:500',
            'supervisor'=>'required|max:190',
            'witness'=>'nullable|max:500',
            'injury_report_datetime'=>'required|before:tomorrow',
            'comment'=>'nullable|max:500',
            
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Polje je obavezno',
            'name.max' => 'Polje može sadržati najviše 190 karaktera',
            'injury_type.required' => 'Polje je obavezno',
            'injury_type.max' => 'Polje može sadržati najviše 190 karaktera',
            'jobs_and_tasks_he_performs.required' => 'Polje je obavezno',
            'jobs_and_tasks_he_performs.max' => 'Polje može sadržati najviše 500 karaktera',
            'injury_datetime.required' => 'Polje je obavezno',
            'injury_datetime.before' => 'Datum ne može biti u budućnosti',
            'injury_cause.required' => 'Polje je obavezno',
            'injury_cause.max' => 'Polje može sadržati najviše 500 karaktera',
            'injury_description.required' => 'Polje je obavezno',
            'injury_description.max' => 'Polje može sadržati najviše 500 karaktera',
            'error.required' => 'Polje je obavezno',
            'error.max' => 'Polje može sadržati najviše 500 karaktera',
            'order_from.required' => 'Polje je obavezno',
            'order_from.max' => 'Polje može sadržati najviše 190 karaktera',
            'dangers_and_risks.required' => 'Polje je obavezno',
            'dangers_and_risks.max' => 'Polje može sadržati najviše 500 karaktera',
            'protective_equipment.required' => 'Polje je obavezno',
            'protective_equipment.max' => 'Polje može sadržati najviše 500 karaktera',
            'high_risk_jobs.required' => 'Polje je obavezno',
            'high_risk_jobs.max' => 'Polje može sadržati najviše 500 karaktera',
            'job_requirements.required' => 'Polje je obavezno',
            'job_requirements.max' => 'Polje može sadržati najviše 500 karaktera',
            'supervisor.required' => 'Polje je obavezno',
            'supervisor.max' => 'Polje može sadržati najviše 190 karaktera',
            'witness.max' => 'Polje može sadržati najviše 500 karaktera',
            'injury_report_datetime.required' => 'Polje je obavezno',
            'injury_report_datetime.before' => 'Datum ne može biti u budućnosti',
            'comment.max' => 'Polje može sadržati najviše 500 karaktera'
            
        ];
    }

    protected function prepareForValidation(): void
    {
    $this->merge([
        
        'standard_id' => session('standard'),
        'team_id' => Auth::user()->current_team_id,
        'user_id' => Auth::user()->id,
        'injury_datetime' => date('Y-m-d H:i', strtotime($this->injury_datetime)),
        'injury_report_datetime' => date('Y-m-d H:i', strtotime($this->injury_report_datetime))
    ]);
    }
}
