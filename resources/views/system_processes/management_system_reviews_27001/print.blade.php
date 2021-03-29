<div >
    
  
    <div ><h2>{{__('Zapisnik sa preispitivanja')}} {{$msr->year}}</h2></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Učestvovali u preispitivanju') }} - {{ $msr->participants }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Status mera iz prethodnog preispitivanja')}} - {{ $msr->measures_status }}</p></div>
   

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Promene u eksternim i internim pitanjima koje su relevantne za sistem menadžmenta') }} - {{ $msr->internal_external_changes }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Obim ispunjenosti ciljeva')}} - {{ $msr->objectives_scope }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Neusaglašenosti i korektivne mere') }} - {{ $msr->inconsistancies_corrective_measures }}</p></div>

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Rezultati praćenja i merenja')}} - {{ $msr->monitoring_measurement_results }}</p></div>
    
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Rezultati internih provera')}} - {{ $msr->checks_results }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Rezultati eksternih provera')}} - {{ $msr->checks_results_desc }}</p></div>
      
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Efektivnost mera koje se odnose na rizike i prilike')}} - {{ $msr->measures_effectiveness }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Povratne informacije od zainteresovanih strana')}} - {{ $msr->relevant_communication_with_stakeholders }}</p></div>

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Prilike za poboljšanja')}} - {{ $msr->improvement_opportunities }}</p></div>
    
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Potrebe za izmenama u sistemu menadžmenta')}} - {{ $msr->needs_for_change }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Potrebe za resursima')}} - {{ $msr->needs_for_resources }}</p></div>
   

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }} - {{$msr->user->name}} </p></div>
            
</div>
                                           
<script>window.print()</script>