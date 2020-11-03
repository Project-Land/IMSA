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
            'date' => 'required',
            'sector_id' => 'required',
            'standard_id' => 'required',
            'leaders' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'date.required' => 'Unesite termin provere',
            'standard_id.required' => 'Izaberite standard',
            'sector_id.required' => 'Izaberite područje provere',
            'leaders.required'=> 'Unesite proveravače'
        ];
    }
}
