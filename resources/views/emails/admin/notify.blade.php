@component('mail::message',['msg'=>$msg,'url'=>$url,'standard_name'=>$standard_name])
# {{__('Obaveštenje')}} {{__('za')}} {{'Standard - '.$standard_name}}


{{$msg}}


@component('mail::button', ['url' => $url])
{{__('Pregledaj u aplikaciji')}}
@endcomponent


{{ config('app.name') }}
@endcomponent
