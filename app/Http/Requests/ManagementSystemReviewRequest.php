<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ManagementSystemReviewRequest extends FormRequest
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
        if ($this->isMethod('post')) {
            return $this->createRules();
        } elseif ($this->isMethod('put')) {
            return $this->updateRules();
        }
    }

    public function createRules()
    {
        return [
            'year' => [
                Rule::unique('management_system_reviews')->where(
                    function ($query) {
                        return $query->where('year', $this->year)->where('team_id', Auth::user()->current_team_id)->where('standard_id', session('standard'))->where('deleted_at', NULL);
                    }
                )
            ],
            //'participants' => 'required',
            //'measures_status' => 'required',
            //'internal_external_changes' => 'required',
            //'monitoring_measurement_results' => 'required',
            //'customer_satisfaction' => 'required',
            //'resource_adequacy' => 'required',
            //'checks_results_desc' => 'required'
        ];
    }

    public function updateRules()
    {
        return [
            //'participants' => 'required',
            //'measures_status' => 'required',
            //'internal_external_changes' => 'required',
            //'monitoring_measurement_results' => 'required',
            //'customer_satisfaction' => 'required',
            //'resource_adequacy' => 'required',
            //'checks_results_desc' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'year.unique' => __('Izveštaj za izabranu godinu već postoji'),
            'participants.required' => __('Polje je obavezno'),
            'measures_status.required' => __('Polje je obavezno'),
            'internal_external_changes.required' => __('Polje je obavezno'),
            'customer_satisfaction.required' => __('Polje je obavezno'),
            'monitoring_measurement_results.required' => __('Polje je obavezno'),
            'resource_adequacy.required' => __('Polje je obavezno'),
            'checks_results_desc.required' => __('Polje je obavezno')
        ];
    }

    protected function prepareForValidation(): void
    {
        $standardId = session('standard');

        if (session('standard_name') == 9001) {
            $this->merge([
                'external_suppliers_performance' => \App\Models\Supplier::getStats(Auth::user()->current_team_id, $standardId, $this->year),
            ]);
        }

        if (session('standard_name') == 14001) {
            $this->merge([
                'environmental_aspects' => \App\Models\EnvironmentalAspect::getStats(Auth::user()->current_team_id, $standardId, $this->year),
                'fulfillment_of_obligations' => \App\Models\EvaluationOfLegalAndOtherRequirement::getStats(Auth::user()->current_team_id, $standardId, $this->year)
            ]);
        }

        if (session('standard_name') == 45001) {
            $this->merge([
                'fulfillment_of_obligations' => \App\Models\EvaluationOfLegalAndOtherRequirement::getStats(Auth::user()->current_team_id, $standardId, $this->year),
                'incidents' => \App\Models\Accident::getStats(Auth::user()->current_team_id, $standardId, $this->year)
            ]);
        }

        $this->merge([
            'user_id' => Auth::user()->id,
            'team_id' => Auth::user()->current_team_id,
            'standard_id' => (int)$standardId,
            'objectives_scope' => \App\Models\Goal::getStats(Auth::user()->current_team_id, $standardId, $this->year),
            'inconsistancies_corrective_measures' => \App\Models\CorrectiveMeasure::getStats(Auth::user()->current_team_id, $standardId, $this->year),
            'checks_results' => \App\Models\PlanIp::getStats(Auth::user()->current_team_id, $standardId, $this->year),
            'measures_effectiveness' => \App\Models\RiskManagement::getStats(Auth::user()->current_team_id, $standardId, $this->year),
        ]);
    }
}
