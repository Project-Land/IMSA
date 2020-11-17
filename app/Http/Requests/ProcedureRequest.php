<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ProcedureRequest extends FormRequest
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
            'version' => 'required',
            'file' => 'required|max:10000|mimes:pdf',
            'sector_id' => 'required'
        ];
    }

    public function updateRules()
    {
        return [
            'document_name' => 'required|max:255',
            'version' => 'required',
            'file' => 'max:10000|mimes:pdf',
            'sector_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'Izaberite fajl',
            'file.mimes' => 'Fajl mora biti u PDF formatu',
            'document_name.required' => 'Unesite naziv dokumenta',
            'document_name.max' => 'Naziv dokumenta ne sme biti duži od 255 karaktera',
            'version.required' => 'Unesite verziju dokumenta',
            'sector_id.required' => 'Izaberite pripadajući sektor'
        ];
    }

    protected function prepareForValidation(): void
    {
        if($this->file){
            $this->merge([ 'file_name' => 'procedure_'.time().'.'.$this->file->getClientOriginalExtension() ]);
        }

        if($this->isMethod('post')){
            $standardId = session('standard');

            $this->merge([
                'user_id' => Auth::user()->id,
                'team_id' => Auth::user()->current_team_id,
                'standard_id' => (int)$standardId,
                'doc_category' => 'procedure'
            ]);
        }
    }
}
