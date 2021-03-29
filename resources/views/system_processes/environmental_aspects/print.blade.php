<div >
    
  
    <div ><h2>{{__('Aspekt životne sredine')}}</h2></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Proces') }} - {{ __($env->process) }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Otpad / Fizičko-hemijska pojava')}} - {{ $env->waste }}</p></div>
   

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Aspekt') }} - {{ $env->aspect }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Uticaj') }} - {{ $env->influence }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Karakter otpada') }} - {{ $env->waste_type }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Verovatnoća pojavljivanja') }} - {{ $env->probability_of_appearance }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Verovatnoća otkrivanja') }} - {{ $env->probability_of_discovery }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Ozbiljnost posledica') }} - {{ $env->severity_of_consequences }}</p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Procenjeni uticaj') }} - {{ $env->estimated_impact }}</p></div>
    
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao')}} - {{ $env->user->name }}</p></div>
   

            
</div>
                                           
<script>window.print()</script>