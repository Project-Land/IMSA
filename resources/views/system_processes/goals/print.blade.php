<div >
    
  
    <div ><h2> {{ $goal->goal }}</h2></div>
    
  
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Godina')}} - {{ $goal->year }}</p></div>
   

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Nivo važnosti') }} - {{ $goal->level }}</p></div>
        
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Rok za realizaciju cilja') }} -  {{ date_format(date_create($goal->deadline),'d.m.Y')}} </p></div>

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Odgovornost za praćenje i realizaciju cilja') }} - {{$goal->responsibility}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Resursi') }} - {{ $goal->resources ??  '/'}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('KPI') }} - {{ $goal->kpi ??  '/' }} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Aktivnosti') }} - {{ $goal->activities ??  '/' }} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Da li je cilj ispunjen') }} - {{ $goal->status ?  __('Da') : __('Ne') }}
    </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Analiza') }} - {{$goal->analysis ?? '/'}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }} - {{$goal->user->name}} </p></div>
            
</div>
                                           
<script>window.print()</script>