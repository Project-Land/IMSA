@component('mail::message',['name'=>$name,'company'=>$company,'email'=>$email,'date'=>$date,'message'=>$message])
# Zahtev za prezentaciju

Od: {{$name}} <br>
Firma: {{$company}} <br>
Termin: {{$date}} <br>
Email: {{$email}} <br>
Poruka: {{$message}} <br>




{{ config('app.name') }}
@endcomponent
