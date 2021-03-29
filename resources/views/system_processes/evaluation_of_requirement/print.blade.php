<div >
    
  
    <div ><h2>{{__('Vrednovanje zakonskih i drugih zahteva')}}</h2></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Nivo sa kojeg zahtev potiče') }} - {{ __($requirement->requirement_level) }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Naziv dokumenta/zakona, ili opis zahteva')}} - {{ $requirement->document_name }}</p></div>
   

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Ocena usaglašenosti') }} - {{ $requirement->compliance ? __('Usaglašen') : __('Neusaglašen') }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Napomena') }} - {{ $requirement->note }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao')}} - {{ $requirement->user->name }}</p></div>
    <h2 >{{ __('Neusaglašenosti i korektivne mere') }}</h2>
@forelse($requirement->correctiveMeasures as $cm)
  <h3 >{{ $cm->name }}</h3>
  
  <div ><p>{{ __('Datum kreiranja') }} -   {{ $cm->created_at->format("d.m.Y H:i") }}</p></div>
  

  <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Sistem menadžment')}} - {{$cm->standard->name }}</p></div>
 

  <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Izvor neusaglašenosti') }} - {{ __($cm->noncompliance_source) }}</p></div>
      
  <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Organizaciona celina') }} -  {{$cm->sector->name}} </p></div>

  <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Opis neusaglašenosti') }} - {{$cm->noncompliance_description}} </p></div>
  <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Uzrok neusaglašenosti') }} - {{ $cm->noncompliance_cause}} </p></div>
  <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Mera za otklanjanje neusaglašenosti') }} - {{ $cm->measure}} </p></div>
  <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Rok za realizaciju korektivne mere') }} - {{ $cm->deadline_date ? (date_format(date_create($cm->deadline_date),"d.m.Y")) : '/' }} </p></div>
  <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Mera odobrena') }} - {{$cm->measure_approval ? __('Odobrena') : __('Neodobrena')}} </p></div>
  <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Razlog neodobravanja mere') }} - {{$cm->measure_approval_reason ?? '/'}}   </p></div>
  <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Datum odobravanja mere') }} - {{ date_format(date_create($cm->measure_approval_date),"d.m.Y")}} </p></div>
  <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Mera efektivna') }} - {{$cm->measure_effective !== null ? ($cm->measure_effective == 1 ? __('Da') : __('Ne'))  : '/'}} </p></div>
@empty
    /
@endforelse
            
</div>
                                           
<script>window.print()</script>