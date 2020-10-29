<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainingRequest extends FormRequest
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
            'name' => 'required|max:190',
            'description' => 'required',
            'num_of_employees' => 'required',
            'description' => 'required',
            'place' => 'required|max:190',
            'resources' => 'required',
            'training_date' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Unesite naziv obuke',
            'name.max' => 'Naziv može sadržati najviše 190 karaktera',
            'desription.required' => 'Unesite opis obuke',
            'num_of_employees.required' => 'Unesite broj zaposlenih',
            'place.required' => 'Unesite mesto obuke',
            'place.max' => 'Polje može sadržati najviše 190 karaktera',
            'resources.required' => 'Unesite potrebne resurse',
            'training_date.required' => 'Unesite datum i vreme obuke'
        ];
    }
}
