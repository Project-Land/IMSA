<div >
    
  
    <div ><h2>{{__('Izveštaj')}}</h2></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold">
        <p>{{__('Prezime, ime povređenog').'/'.__('aktera incidenta') }} - {{ $accident->name }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Poslovi i zadaci koje obavlјa')}} - {{ $accident->jobs_and_tasks_he_performs }}</p></div>
   

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold">
        <p>{{ __('Datum i vreme povrede').'/'.__('incidenta') }} - {{$accident->injury_datetime ? date_format(date_create($accident->injury_datetime),'d.m.Y H:i') : '/'}}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Uzrok povrede').'/'.__('incidenta')}} - {{ $accident->injury_cause ?? '/'  }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('KRATAK OPIS POVREDE/INCIDENTA (kako je došlo do povrede/incidenta - u fazama)') }} - {{ $accident->injury_description }}</p></div>

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Šta je greška?')}} - {{ $accident->error ?? '/' }}</p></div>
    
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Po čijem je nalogu radio?')}} - {{ $accident->order_from ?? '/' }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Da li je obučen za rad i upoznat sa opasnostima i rizicima za te poslove?')}} - {{ $accident->dangers_and_risks }}</p></div>
      
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Da li je koristio predviđena lična zaštitna sredstva i opremu i koju?')}} - {{ $accident->protective_equipment }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Da li je radio na poslovima sa povećanim rizikom?')}} - {{ $accident->high_risk_jobs }}</p></div>

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Da li ispunjava sve uslove za te poslove?')}} - {{ $accident->job_requirements }}</p></div>
    
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Podaci o svedoku-očevicu: Ime, prezime i broj telefona (ako je bilo)')}} - {{ $accident->witness ?? '/' }}</p></div>

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Podaci o neposrednom rukovodiocu povređenog/aktera incidenta: Ime, prezime i radno mesto')}} - {{ $accident->supervisor ?? '/' }}</p></div>
   
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Datum i vreme prijave povrede/incidenta')}} - {{ $accident->injury_report_datetime ? date_format(date_create($accident->injury_report_datetime),'d.m.Y H:i') : '/' }}</p></div>

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Zapažanje/komentar')}} - {{ $accident->comment ?? '/' }}</p></div>

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }} - {{$accident->user->name}} </p></div>
            
</div>
                                           
<script>window.print()</script>