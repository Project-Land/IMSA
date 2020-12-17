<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

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
            'version' => 'required',
            'file' => 'required|max:10000|mimes:pdf,csv,xls,xlsx,doc,docx,jpeg,png,bmp,webp,gif',
            'sector_id' => 'required'
        ];
    }

    public function updateRules()
    {
        return [
            'document_name' => 'required|max:255',
            'version' => 'required',
            'file' => 'max:10000|mimes:pdf,csv,xls,xlsx,doc,docx,jpeg,png,bmp,webp,gif',
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
            'file.required' => __('Izaberite fajl'),
            'document_name.required' => __('Unesite naziv dokumenta'),
            'document_name.max' => __('Naziv dokumenta ne sme biti duži od 255 karaktera'),
            'version.required' => __('Unesite verziju dokumenta'),
            'sector_id.required' => __('Izaberite pripadajući sektor'),
            'file.max' => __('Fajl ne sme biti veći od 10mb'),
            'file.mimes' => __('Fajl mora biti u nekom od dozvoljenih formata: pdf, csv, xls, xlsx, doc, docx, jpeg, png, bmp, webp, gif')
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
