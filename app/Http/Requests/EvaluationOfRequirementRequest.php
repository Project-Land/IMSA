<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class EvaluationOfRequirementRequest extends FormRequest
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
            'document_name' => 'required|max:190',
            'requirement_level' => 'required',
            'compliance' => 'required',
            'note' => 'nullable|max:190',
        ];
    }

    public function updateRules()
    {
        return [
            'document_name' => 'required|max:190',
            'requirement_level' => 'required',
            'compliance' => 'required',
            'note' => 'nullable|max:190',
        ];
    }

    public function messages()
    {
        return [
            'document_name.required' => __('Polje je obavezno'),
            'document_name.max' => __('Polje može sadržati najviše 190 karaktera'),
            'requirement_level.required' => __('Polje je obavezno'),
            'compliance.required' => __('Polje je obavezno'),
            'note.max' => __('Polje može sadržati najviše 190 karaktera'),
           
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => Auth::user()->id,
            'team_id' => Auth::user()->current_team_id,
            'standard_id' => session('standard'),
        
        ]);
    }
}
