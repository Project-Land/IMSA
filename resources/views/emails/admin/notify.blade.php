@component('mail::message',['msg'=>$msg,'url'=>$url,'standard_name'=>$standard_name])
# Obaveštenje za {{'Standard - '.$standard_name}}


{{$msg}}


@component('mail::button', ['url' => $url])
Pregledaj u aplikaciji
@endcomponent


{{ config('app.name') }}
@endcomponent
