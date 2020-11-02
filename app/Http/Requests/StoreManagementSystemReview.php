<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreManagementSystemReview extends FormRequest
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
            'year' => "unique:management_system_reviews",
            'participants' => 'required',
            'measures_status' => 'required',
            'internal_external_changes' => 'required',
            'monitoring_measurement_results' => 'required',
            'customer_satisfaction' => 'required',
            'resource_adequacy' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'year.unique' => 'Izveštaj za izabranu godinu već postoji',
            'participants.required' => 'Unesite učesnike',
            'measures_status.required' => 'Unesite status mera',
            'internal_external_changes.required' => 'Unesite promene',
            'customer_satisfaction.required' => 'Unesite zadovoljstvo klijenata',
            'monitoring_measurement_results.required' => 'Unesite rezultate praćenja merenja',
            'resource_adequacy.required' => 'Unesite adekvatnost resursa'
        ];
    }

    protected function prepareForValidation(): void
    {
        $standardId = session('standard');

        $this->merge([
            'user_id' => \Auth::user()->id,
            'team_id' => \Auth::user()->current_team_id,
            'standard_id' => (int)$standardId,
            'objectives_scope' => \App\Models\Goal::getStats($standardId, $this->year),
            'inconsistancies_corrective_measures' => \App\Models\CorrectiveMeasure::getStats($standardId, $this->year),
            'checks_results' => \App\Models\PlanIp::getStats($standardId, $this->year),
            'external_suppliers_performance' => \App\Models\Supplier::getStats($standardId, $this->year),
            'measures_effectiveness' => \App\Models\RiskManagement::getStats($standardId, $this->year),
        ]);
    }
}