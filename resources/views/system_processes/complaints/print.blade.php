<div >
    
  
    <div ><h2>{{__('Reklamacija')}}</h2></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Oznaka')}} - {{ $complaint->name }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Opis')}} - {{ $complaint->description }}</p></div>
   

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Datum podnošenja') }} - {{ date_format(date_create($complaint->submission_date),'d.m.Y') }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Proces na koji se reklamacija odnosi') }} - {{ $complaint->sector->name }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Opravdana / prihvaćena') }} - {{ $complaint->accepted ? __('Da') : __('Ne') }}</p></div>

   
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Rok za realizaciju') }} - {{ date_format(date_create($complaint->deadline_date),'d.m.Y') }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Lice odgovorno za rešavanje') }} - {{ $complaint->responsible_person }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Način rešavanja') }} - {{ $complaint->way_of_solving }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Status') }} - {{ $complaint->status ? __('Otvorena') :  __('Zatvorena') }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Datum zatvaranja') }} - {{ date_format(date_create($complaint->closing_date),'d.m.Y') }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }} - {{$complaint->user->name}} </p></div>
            
</div>
                                           
<script>window.print()</script>