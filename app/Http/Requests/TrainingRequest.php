<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class TrainingRequest extends FormRequest
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
            'name' => 'required|max:190',
            'description' => 'required',
            'num_of_employees' => 'required|numeric',
            'description' => 'required',
            'place' => 'required|max:190',
            'resources' => 'required',
            'training_date' => 'required|after:yesterday',
            'file[]'=>'nullable'
            
            
        ];
    }

    public function updateRules()
    {
        return [
            'name' => 'required|max:190',
            'description' => 'required',
            'num_of_employees' => 'required|numeric',
            'description' => 'required',
            'place' => 'required|max:190',
            'resources' => 'required',
            'training_date' => 'required',
            'file[]'=>'nullable',
            'new_file[]'=>'nullable',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Unesite naziv obuke',
            'name.max' => 'Naziv može sadržati najviše 190 karaktera',
            'description.required' => 'Unesite opis obuke',
            'num_of_employees.required' => 'Unesite broj zaposlenih',
            'num_of_employees.numeric' => 'Polje mora biti broj',
            'place.required' => 'Unesite mesto obuke',
            'place.max' => 'Polje može sadržati najviše 190 karaktera',
            'resources.required' => 'Unesite potrebne resurse',
            'training_date.required' => 'Unesite datum i vreme obuke',
            'training_date.after' => 'Unesite budući datum'
        ];
    }

    protected function prepareForValidation(): void
    {
        if(!$this->file){
            $this->merge([
                'file' => []]);
        }
        
    
        $this->merge([
            'standard_id' => session('standard'),
            'team_id' => Auth::user()->current_team_id,
            'user_id' => Auth::user()->id,
            'year' => date('Y', strtotime($this->training_date)),
            'training_date' => $this->training_date != null ? date('Y-m-d H:i:s', strtotime($this->training_date)) : null,
            'final_num_of_employees' => $this->final_num_of_employees != null ? $this->final_num_of_employees : null,
            'rating' => $this->rating != null ? $this->rating : null,
            
        ]);
    }
}
