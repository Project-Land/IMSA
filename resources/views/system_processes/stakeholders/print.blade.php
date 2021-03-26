<div >
    
  
    <div ><h2>{{__('Zainteresovana strana')}}</h2></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Naziv / Ime')}} - {{ $stakeholder->name }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Potrebe i očekivanja zainteresovane strane')}} - {{ $stakeholder->expectation }}</p></div>
   

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Odgovor preduzeća na potrebe i očekivanja') }} - {{ $stakeholder->response }}</p></div>
        
   
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }} - {{$stakeholder->user->name}} </p></div>
            
</div>
                                           
<script>window.print()</script>