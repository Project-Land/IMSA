<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanIpRequest extends FormRequest
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
            //'checked_date' => 'required|date|after:yesterday',
            //'checked_sector'=>'required',
            //'team_for_internal_check' => 'required',
            'check_start' => 'required|date|after:checked_date',
            'check_end' => 'required|date|after:check_start',
            'report_deadline' => 'required|date|after:check_end'
        ];
    }

    public function messages()
    {
        return [
            'checked_date.required' => 'Unesite termin provere',
            'checked_date.after' => 'Unesite budući datum',
            'checked_sector.required' => 'Unesite sektor',
            'team_for_internal_check.required' => 'Unesite tim za proveru',
            'check_start.required' => 'Unesite početak provere',
            'check_start.after' => 'Unesite datum noviji od termina provere',
            'check_end.required' => 'Unesite završetak provere',
            'check_end.after' => 'Unesite datum noviji od početka provere',
            'report_deadline.required' => 'Unesite rok za dostavljanje izveštaja',
            'report_deadline.after' => 'Unesite datum noviji od završetka provere',
            'checked_date.date' => 'Format datuma nije odgovarajuć',
            'check_start.date' => 'Format datuma nije odgovarajuć',
            'check_end.date' => 'Format datuma nije odgovarajuć',
            'report_deadline.date' => 'Format datuma nije odgovarajuć',
        ];
    }
}
