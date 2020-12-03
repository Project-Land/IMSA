<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class GoalsRequest extends FormRequest
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
            'responsibility' => 'required',
            'goal' => 'required',
            'deadline' => 'required|after:yesterday',
            'kpi' => 'required',
            'resources' => 'required',
            'activities' => 'required'
        ];
    }

    public function updateRules()
    {
        return [
            'responsibility' => 'required',
            'goal' => 'required',
            'deadline' => 'required',
            'kpi' => 'required',
            'resources' => 'required',
            'activities' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'responsibility.required' => 'Unesite odgovornost',
            'goal.required' => 'Unesite cilj',
            'deadline.required' => 'Unesite rok za realizaciju',
            'deadline.after' => 'Unesite budući datum',
            'kpi.required' => 'Unesite KPI',
            'resources.required' => 'Unesite resurse',
            'activities.required' => 'Unesite aktivnosti'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'standard_id' => session('standard'),
            'team_id' => Auth::user()->current_team_id,
            'user_id' => Auth::user()->id,
            'deadline' => $this->deadline != null ? date('Y-m-d', strtotime($this->deadline)) : null,
            'analysis' => $this->analysis != null ? $this->analysis : null
        ]);
    }
}
