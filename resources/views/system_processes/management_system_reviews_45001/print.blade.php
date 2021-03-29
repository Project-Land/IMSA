<div >
    
  
    <div ><h2>{{__('Zapisnik sa preispitivanja')}} {{$msr->year}}</h2></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Učestvovali u preispitivanju') }} - {{ $msr->participants }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Status mera iz prethodnog preispitivanja')}} - {{ $msr->measures_status }}</p></div>
   
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Promene u eksternim i internim pitanjima koje su relevantne za sistem menadžmenta') }} - {{ $msr->internal_external_changes }}</p></div>

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Potrebe i očekivanja zainteresovanih strana i obaveze za usklađenost') }} - {{ $msr->customer_satisfaction }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Obim ispunjenosti ciljeva')}} - {{ $msr->objectives_scope }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Incidenti')}} - {{ $msr->incidents }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Neusaglašenosti i korektivne mere') }} - {{ $msr->inconsistancies_corrective_measures }}</p></div>

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Rezultati praćenja i merenja')}} - {{ $msr->monitoring_measurement_results }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Ispunjenost obaveza za usklađenost')}} - {{ $msr->fulfillment_of_obligations }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Rezultati internih provera')}} - {{ $msr->checks_results }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Rezultati eksternih provera')}} - {{ $msr->checks_results_desc }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Konsultovanje i učestvovanje radnika')}} - {{ $msr->consulting_and_employee_participation }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Adekvatnost resursa')}} - {{ $msr->resource_adequacy }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Efektivnost mera koje se odnose na rizike i prilike')}} - {{ $msr->measures_effectiveness }}</p></div>
   
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__(' Relevantno komuniciranje sa zainteresovanim stranama')}} - {{ $msr->relevant_communication_with_stakeholders }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Prilike za poboljšanje')}} - {{ $msr->improvement_opportunities }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Pogodnost, adekvatnost i efektivnost OH&S sistema menadžmenta')}} - {{ $msr->cae }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Prilike za stalna poboljšanja')}} - {{ $msr->continous_improvement_opportunities }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Potrebe za izmenama u sistemu menadžmenta')}} - {{ $msr->needs_for_change }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Mere, ako su potrebne')}} - {{ $msr->measures_optional }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Prilike za poboljšanje i integrisanje sa drugim procesima i sistemima menadžmenta')}} - {{ $msr->opportunities }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Eventualne posledice po strateško usmerenje organizacije')}} - {{ $msr->consequences }}</p></div>
    
   

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }} - {{$msr->user->name}} </p></div>
            
</div>
                                           
<script>window.print()</script>