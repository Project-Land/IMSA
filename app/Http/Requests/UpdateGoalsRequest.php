<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGoalsRequest extends FormRequest
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
            'responsibility' => 'required|max:190',
            'goal' => 'required|max:190',
            'deadline' => 'required',
            'kpi' => 'required|max:190',
            'resources' => 'required|max:190',
            'activities' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'responsibility.required' => 'Unesite odgovornost',
            'responsibility.max' => 'Polje može sadržati najviše 190 karaktera',
            'goal.required' => 'Unesite cilj',
            'goal.max' => 'Polje može sadržati najviše 190 karaktera',
            'deadline.required' => 'Unesite rok za realizaciju',
            'kpi.required' => 'Unesite KPI',
            'kpi.max' => 'Polje može sadržati najviše 190 karaktera',
            'resources.required' => 'Unesite resurse',
            'resources.max' => 'Polje može sadržati najviše 190 karaktera',
            'activities.required' => 'Unesite aktivnosti'
        ];
    }
}
