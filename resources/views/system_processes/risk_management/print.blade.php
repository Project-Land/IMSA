<div >
    
  
    <div ><h2>{{__('Rizici i prilike')}}</h2></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Opis')}} - {{ $risk->description }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Verovatnoća')}} - {{ $risk->probability }}</p></div>
   

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Posledice') }} - {{ $risk->frequency }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Ukupno') }} - {{ $risk->total }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Prihvatljivo') }} - {{ $risk->acceptable }}</p></div>

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><h3>{{ __($risk->measure) }}</h3></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirano') }} - {{ date_format(date_create($risk->measure_created_at),'d.m.Y') }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Uzrok') }} - {{ $risk->cause }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Mera za smanjenje rizika/ korišćenje prilike') }} - {{ $risk->risk_lowering_measure }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Odgovornost') }} - {{ $risk->responsibility }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Rok za realizaciju') }} - {{ date_format(date_create($risk->deadline),'d.m.Y') }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Status') }} - {{ $risk->status ? __('Otvorena') :  __('Zatvorena') }}</p></div>
        
   
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }} - {{$risk->user->name}} </p></div>
            
</div>
                                           
<script>window.print()</script>