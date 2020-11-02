<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintsRequest extends FormRequest
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
            'name' => 'required|max:190',
            'description' => 'required',
            'submission_date' => 'required',
            'process' => 'required',
            'responsible_person' => 'max:190',
            'way_of_solving' => 'max:190'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Unesite oznaku reklamacije',
            'name.max' => 'Oznaka može sadržati najviše 190 karaktera',
            'desription.required' => 'Unesite opis reklamacije',
            'submission_date.required' => 'Unesite datum podnošenja reklamacije',
            'process.required' => 'Unesite proces na koji se reklamacija odnosi',
            'process.max' => 'Polje može sadržati najviše 190 karaktera',
            'responsible_person.max' => 'Polje može sadržati najviše 190 karaktera',
            'way_of_solving.max' => 'Polje može sadržati najviše 190 karaktera'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => \Auth::user()->id,
            'team_id' => \Auth::user()->current_team_id,
            'standard_id' => session('standard'),
            'submission_date' => date('Y-m-d', strtotime($this->submission_date)),
            'deadline_date' => date('Y-m-d', strtotime($this->deadline_date)),
            'status' => $this->status != null ? $this->status : 1,
            'closing_date' => $this->status == 1 ? date('Y-m-d') : null
        ]);
    }
}
