<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
            'description' => 'required',
            'submission_date' => 'required',
            'sector_id' => 'required',
            'responsible_person' => 'nullable|max:190',
            'way_of_solving' => 'nullable|max:190',
            'deadline_date' => 'nullable|after:submission_date',
            'file[]'=>'nullable',
        ];
    }

    public function updateRules()
    {
        return [
            'name' => 'required|max:190',
            'description' => 'required',
            'submission_date' => 'required',
            'sector_id' => 'required',
            'responsible_person' => 'nullable',
            'way_of_solving' => 'nullable|max:190',
            'deadline_date' => 'nullable|after:submission_date',
            'file[]'=>'nullable',
            'new_file[]'=>'nullable',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('Polje je obavezno'),
            'name.max' => __('Polje može sadržati najviše 190 karaktera'),
            'desription.required' => __('Polje je obavezno'),
            'submission_date.required' => __('Polje je obavezno'),
            'sector_id.required' => __('Polje je obavezno'),
            'way_of_solving.max' => __('Polje može sadržati najviše 190 karaktera'),
            'deadline_date.after' => __('Unesite datum noviji od datuma podnošenja reklamacije')
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => Auth::user()->id,
            'team_id' => Auth::user()->current_team_id,
            'standard_id' => session('standard'),
            'submission_date' => $this->submission_date != null ?  date('Y-m-d', strtotime($this->submission_date)) : null,
            'deadline_date' => $this->deadline_date != null ? date('Y-m-d', strtotime($this->deadline_date)) : null,
            'status' => $this->status != null ? $this->status : 0,
            'closing_date' => $this->status == 0 ? date('Y-m-d') : null,
        ]);
    }
}
