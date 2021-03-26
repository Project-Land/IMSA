<div >
    
  
    <div ><h2>{{__('Merna oprema')}}</h2></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Oznaka merne opreme')}} - {{ $me->	label }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Naziv merne opreme')}} - {{ $me->name }}</p></div>
   

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Datum poslednjeg etaloniranja/baždarenja') }} - {{ $me->last_calibration_date ? date_format(date_create($me->last_calibration_date),'d.m.Y') : '/' }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Datum sledećeg etaloniranja/baždarenja') }} - {{ $me->next_calibration_date ? date_format(date_create($me->next_calibration_date),'d.m.Y') : '/' }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }} - {{$me->user->name}} </p></div>
            
</div>
                                           
<script>window.print()</script>