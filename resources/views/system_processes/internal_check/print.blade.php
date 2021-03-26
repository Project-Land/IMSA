
<div >
    <h2 >{{ __('Interna provera') }}</h2>
  
    <div ><p>{{ __('Termin provere') }} -   {{ date_format(date_create($ic->date),"d.m.Y") }}</p></div>
    
  
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Vođe tima i proveravači')}} - {{$ic->leaders ?? "/" }}</p></div>
   

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Područje provere') }} - {{ $ic->sector->name }}</p></div>
        
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Standard') }} -  {{$ic->standard->name}} </p></div>

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Br programa IP') }} - {{$ic->planIp->name}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Početak provere') }} - {{ $ic->planIp->check_start ? date_format(date_create($ic->planIp->check_start),"d.m.Y") : '/'}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Završetak provere') }} - {{ $ic->planIp->check_end ? date_format(date_create($ic->planIp->check_end),"d.m.Y") : '/'}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Rok za dostavljanje izveštaja') }} - {{ $ic->planIp->report_deadline ? date_format(date_create($ic->planIp->report_deadline),"d.m.Y") : '/'}} </p></div>
        
        
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }} - {{$ic->user->name}} </p></div>

    @if($ic->internalCheckReport)
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><h3>{{ __('Izveštaj sa interne provere') }}  </h3></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Specifikacija dokumenata') }} - {{$ic->internalCheckReport->specification}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><h3>{{ __('Neusaglašenost') }}  </h3></div>
    @forelse($ic->internalCheckReport->correctiveMeasures as $correctiveMeasure)
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Opis neusaglašenosti') }} - {{$correctiveMeasure->noncompliance_description}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Uzrok neusaglašenosti') }} - {{$correctiveMeasure->noncompliance_cause}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Mera za otklanjanje neusaglašenosti') }} - {{$correctiveMeasure->measure}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Rok za realizaciju korektivne mere') }} - {{ date_format(date_create($correctiveMeasure->deadline_date),"d.m.Y")}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Mera odobrena') }} - {{$correctiveMeasure->measure_approval ? __('Da') : __('Ne')}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Razlog neodobravanja mere') }} - {{$correctiveMeasure->measure_approval_reason ?? '/'}}   </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Datum odobravanja mere') }} - {{ date_format(date_create($correctiveMeasure->measure_approval_date),"d.m.Y")}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Mera efektivna') }} - {{$correctiveMeasure->measure_effective !== null ? ($correctiveMeasure->measure_effective == 1 ? __('Da') : __('Ne'))  : '/'}} </p></div>
    @empty
    <div>/</div>
    @endforelse
    @endif

    @if($ic->internalCheckReport->recommendations)
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><h4>{{ __('Preporuka') }}  </h4></div>
    @foreach($ic->internalCheckReport->recommendations as $rec)
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ $rec->description }}  </p></div>
    @endforeach
    @endif

    
            
</div>
                                           
<script>window.print()</script>