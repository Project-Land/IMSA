<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CertDocumentRequest extends FormRequest
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
            'name' => 'required|max:255',
            'name' => Rule::unique('cert_documents')->where( function ($query) {
                return $query->where([
                    ['team_id', Auth::user()->current_team_id],
                ])->whereNull('deleted_at');
            }),
            'file' => 'required|max:50000|mimes:pdf,doc,docx,jpg,png'
        ];
    }

    public function updateRules()
    {
        return [
            'name' => 'required|max:255',
            'name' => Rule::unique('cert_documents')->ignore($this->route('certification-documents'))->where( function ($query) {
                return $query->where([
                    ['team_id', Auth::user()->current_team_id]
                ])->whereNull('deleted_at');
            }),
            'file' => 'max:50000|mimes:pdf,doc,docx,jpg,png'
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'Izaberite fajl',
            'file.mimes' => 'Fajl mora biti u nekom od dozvoljenih formata: pdf, doc, docx, jpg, png',
            'file.max' => 'Dokument ne sme biti veći od 50mb',
            'name.required' => 'Unesite naziv dokumenta',
            'name.unique' => "Već postoji dokument sa takvim nazivom",
            'name.max' => 'Naziv dokumenta ne sme biti duži od 255 karaktera',
        ];
    }

    protected function prepareForValidation(): void
    {
        if($this->file){
            $this->merge([ 'file_name' => 'cert_document_'.time().'.'.$this->file->getClientOriginalExtension() ]);
        }

        if($this->isMethod('post')){
            $this->merge([
                'user_id' => Auth::user()->id,
                'team_id' => Auth::user()->current_team_id,
                'start_date' => date('Y-m-d', strtotime($this->start_date)),
                'end_date' => date('Y-m-d', strtotime($this->end_date)),
            ]);
        }
    }
}
