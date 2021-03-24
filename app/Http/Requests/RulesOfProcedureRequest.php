<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RulesOfProcedureRequest extends FormRequest
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
            'document_name' => 'required|max:255',
            'document_name' => Rule::unique('documents')->where( function ($query) {
                return $query->where('team_id', Auth::user()->current_team_id)->where('standard_id', session('standard'))->whereNull('deleted_at');
            }),
            'version' => 'required',
            'file' => 'required|max:50000|mimes:pdf'
        ];
    }

    public function updateRules()
    {
        return [
            'document_name' => 'required|max:255',
            'document_name' => Rule::unique('documents')->ignore($this->route('rules_of_procedure'))->where( function ($query) {
                return $query->where('team_id', Auth::user()->current_team_id)->where('standard_id', session('standard'))->whereNull('deleted_at');
            }),
            'version' => 'required',
            'file' => 'max:50000|mimes:pdf'
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'Izaberite fajl',
            'file.mimes' => 'Fajl mora biti u PDF formatu',
            'file.max' => 'Dokument ne sme biti veći od 50mb',
            'document_name.required' => 'Unesite naziv dokumenta',
            'document_name.max' => 'Naziv dokumenta ne sme biti duži od 255 karaktera',
            'version.required' => 'Unesite verziju dokumenta',
            'document_name.unique' => "Već postoji dokument sa takvim nazivom",
        ];
    }

    protected function prepareForValidation(): void
    {
        if($this->file){
            $this->merge([ 'file_name' => 'rules_of_procedure_'.time().'.'.$this->file->getClientOriginalExtension() ]);
        }

        if($this->isMethod('post')){
            $standardId = session('standard');

            $this->merge([
                'user_id' => Auth::user()->id,
                'team_id' => Auth::user()->current_team_id,
                'standard_id' => (int)$standardId,
                'doc_category' => 'rules_procedure'
            ]);
        }
    }
}
