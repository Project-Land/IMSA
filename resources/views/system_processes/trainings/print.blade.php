<div >
    
  
    <div ><h2> {{ $training->name }}</h2></div>
    
  
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Vrsta')}} - {{ __($training->type) }}</p></div>
   

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Opis') }} - {{ __($training->description) }}</p></div>
        
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Broj zaposlenih - planirano') }} -  {{$training->num_of_employees}} </p></div>

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Termin / Mesto') }} - {{date_format(date_create($training->training_date),'d.m.Y H:i').", ".$training->place}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Resursi') }} - {{ $training->resources}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Broj zaposlenih - realizovano') }} - {{ $training->final_num_of_employees ??  '/' }} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Ocena efekata obuke') }} - {{ $training->rating ??  '/' }} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Učesnici') }} - 
        @forelse($training->users as $user)
        {{$user->name.', '}}
        @empty
        /
        @endforelse
    </p></div>
     
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }} - {{$training->user->name}} </p></div>
            
</div>
                                           
<script>window.print()</script>