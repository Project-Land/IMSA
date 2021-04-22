@component('mail::message',['name'=>$name,'company'=>$company,'email'=>$email,'date'=>$date])
# Zahtev za sastanak

Od: {{$name}} <br>
Firma: {{$company}} <br>
Termin: {{$date}} <br>
Email: {{$email}} <br>




{{ config('app.name') }}
@endcomponent
