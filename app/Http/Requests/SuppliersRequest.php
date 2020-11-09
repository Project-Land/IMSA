<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class SuppliersRequest extends FormRequest
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
            'supplier_name' => 'required|max:190',
            'subject' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'supplier_name.required' => 'Unesite naziv isporučioca',
            'supplier_name.max' => 'Naziv može sadržati maksimalno 190 karaktera',
            'subject.required' => 'Unesite predmet nabavke',
        ];
    }

    protected function prepareForValidation(): void
    {
        $total = $this->quality + $this->price + $this->shippment_deadline;

        $deadlineDate = Carbon::parse(Carbon::now()->toDateTimeString())->addMonths(6);
        $this->merge([
            'standard_id' => session('standard'),
            'team_id' => \Auth::user()->current_team_id,
            'user_id' => \Auth::user()->id,
            'status' => $total >= 8.5 ? 1 : 0,
            'deadline_date' => $deadlineDate
        ]);
    }
}