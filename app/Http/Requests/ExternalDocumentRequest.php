<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ExternalDocumentRequest extends FormRequest
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
            'document_name' => 'required|max:255',
            'document_name' => Rule::unique('documents')->where( function ($query) {
                return $query->where([
                    ['team_id', Auth::user()->current_team_id],
                    ['standard_id', session('standard')],
                    ['doc_category', 'external_document']
                ])->whereNull('deleted_at');
            }),
            'file' => 'required|max:50000|mimes:pdf,doc,docx'
        ];
    }

    public function updateRules()
    {
        return [
            'document_name' => 'required|max:255',
            'document_name' => Rule::unique('documents')->ignore($this->route('external_document'))->where( function ($query) {
                return $query->where([
                    ['team_id', Auth::user()->current_team_id],
                    ['standard_id', session('standard')],
                    ['doc_category', 'external_document']
                ])->whereNull('deleted_at');
            }),
            'file' => 'max:50000|mimes:pdf,doc,docx'
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'Izaberite fajl',
            'file.mimes' => 'Fajl mora biti u nekom od dozvoljenih formata: pdf, doc, docx',
            'file.max' => 'Dokument ne sme biti veći od 50mb',
            'document_name.required' => 'Unesite naziv dokumenta',
            'document_name.unique' => "Već postoji dokument sa takvim nazivom",
            'document_name.max' => 'Naziv dokumenta ne sme biti duži od 255 karaktera',
            'document_name.unique' => "Već postoji dokument sa takvim nazivom",
        ];
    }

    protected function prepareForValidation(): void
    {
        if($this->file){
            $this->merge([ 'file_name' => 'external_document_'.time().'.'.$this->file->getClientOriginalExtension() ]);
        }

        if($this->isMethod('post')){
            $standardId = session('standard');

            $this->merge([
                'user_id' => Auth::user()->id,
                'team_id' => Auth::user()->current_team_id,
                'standard_id' => (int)$standardId,
                'doc_category' => 'external_document'
            ]);
        }
    }
}
