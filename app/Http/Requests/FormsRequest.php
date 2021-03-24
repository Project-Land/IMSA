<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormsRequest extends FormRequest
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

    public function createRules()
    {
        return [
            'document_name' => 'required|max:255',
            'document_name' => Rule::unique('documents')->where( function ($query) {
                return $query->where('team_id', Auth::user()->current_team_id)->where('standard_id', session('standard'))->whereNull('deleted_at');
            }),
            'version' => 'required',
            'file' => 'required|max:50000|mimes:pdf,csv,xls,xlsx,doc,docx,jpeg,png,bmp,webp,gif',
            'sector_id' => 'required'
        ];
    }

    public function updateRules()
    {
        return [
            'document_name' => 'required|max:255',
            'document_name' => Rule::unique('documents')->ignore($this->route('form'))->where( function ($query) {
                return $query->where('team_id', Auth::user()->current_team_id)->where('standard_id', session('standard'))->whereNull('deleted_at');
            }),
            'version' => 'required',
            'file' => 'max:50000|mimes:pdf,csv,xls,xlsx,doc,docx,jpeg,png,bmp,webp,gif',
            'sector_id' => 'required'
        ];
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

    public function messages()
    {
        return [
            'file.required' => 'Izaberite fajl',
            'file.max' => 'Dokument ne sme biti veći od 50mb',
            'file.mimes' => 'Fajl mora biti u nekom od dozvoljenih formata: pdf, csv, xls, xlsx, doc, docx, jpeg, png, bmp, webp, gif',
            'document_name.required' => 'Unesite naziv dokumenta',
            'document_name.max' => 'Naziv dokumenta ne sme biti duži od 255 karaktera',
            'document_name.unique' => "Već postoji dokument sa takvim nazivom",
            'version.required' => 'Unesite verziju dokumenta',
            'sector_id.required' => 'Izaberite pripadajući sektor',
        ];
    }

    protected function prepareForValidation(): void
    {
        if($this->file){
            $this->merge([ 'file_name' => 'form_'.time().'.'.$this->file->getClientOriginalExtension() ]);
        }

        if($this->isMethod('post')){
            $standardId = session('standard');

            $this->merge([
                'user_id' => Auth::user()->id,
                'team_id' => Auth::user()->current_team_id,
                'standard_id' => (int)$standardId,
                'doc_category' => 'form'
            ]);
        }
    }
}
