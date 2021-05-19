@component('mail::message', ['url' => $url, 'standard_name' => $standard_name])
# {{__('Obaveštenje')}} {{__('za')}} {{'Standard - '.$standard_name}}


{{ __('Kreiran je novi plan interne provere') }}


@component('mail::button', ['url' => $url])
{{ __('Pregledaj u aplikaciji') }}
@endcomponent


{{ config('app.name') }}
@endcomponent
