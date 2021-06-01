
<div >
    <h2 >{{ __('Zadovoljstvo korisnika') }}</h2>
  
    <div ><p>{{ __('Klijent') }} - {{$cs->customer}}</p></div>
    
    @foreach($cs->columns as $col)
        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{$col->name}} - {{$cs->{$col->column_name} ?? "/" }}</p></div>
    @endforeach

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Prosek') }} - {{ $cs->average }}</p></div>
        
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Napomena') }} -  {{$cs->comment ?? "/"}} </p></div>
        
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Datum') }} - {{ $cs->date ? date('d.m.Y', strtotime($cs->date)) : $cs->created_at->format('d.m.Y H:i') }}</p></div>
        
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }} - {{$cs->user->name}} </p></div>
            
</div>
                                           
<script>window.print()</script>