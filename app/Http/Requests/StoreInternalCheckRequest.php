<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInternalCheckRequest extends FormRequest
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
            'date' => 'required|after:yesterday',
            'sectors' => 'required',
            'leaders' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'date.required' => 'Unesite termin provere',
            'date.after' => 'Unesite budući datum',
            'sectors.required' => 'Izaberite područje provere',
            'leaders.required'=> 'Izaberite proveravače'
        ];
    }
}
