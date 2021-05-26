@component('mail::message')
# {{__('Hvala što ste se prijavili na naš newsletter')}}

{{__('Možete se odjaviti u bilo kom trenutku klikom na dugme ispod')}}

@component('mail::button', ['url' => $url])
{{ __('Odjavite se') }}
@endcomponent
{{__('Hvala')}},<br>
{{ config('app.name') }}
@endcomponent
