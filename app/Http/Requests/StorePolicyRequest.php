<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePolicyRequest extends FormRequest
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
            'document_name' => 'required|max:255',
            'document_version' => 'required',
            'file' => 'required|max:10000|mimes:pdf'
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'Izaberite fajl',
            'file.mimes' => 'Fajl mora biti u PDF formatu',
            'document_name.required' => 'Unesite naziv dokumenta',
            'document_name.max' => 'Naziv dokumenta ne sme biti duži od 255 karaktera',
            'document_version.required' => 'Unesite verziju dokumenta'
        ];
    }
}
