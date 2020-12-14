@component('mail::message',['msg'=>$msg,'url'=>$url,'standard'=>$standard])
# Obaveštenje za {{'Standard - '.$standard}}


{{$msg}}


@component('mail::button', ['url' => $url])
Pregledaj u aplikaciji
@endcomponent


{{ config('app.name') }}
@endcomponent
