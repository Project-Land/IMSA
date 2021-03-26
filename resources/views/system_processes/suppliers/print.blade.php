<div >
    
  
    <div ><h2> {{ $supplier->supplier_name }}</h2></div>
    
  
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{__('Datum kreiranja')}} - {{ $supplier->created_at->format('d.m.Y H:i') }}</p></div>
   

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Predmet nabavke') }} - {{ $supplier->subject }}</p></div>
        
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Ime i prezime kontakt osobe kod isporučioca') }} -  {{ $supplier->personal_info ?? '/' }} </p></div>

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Broj telefona kontakt osobe kod isporučioca') }} - {{ $supplier->phone_number ?? '/'}} </p></div>

    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Email kontakt osobe kod isporučioca') }} - {{ $supplier->email ??  '/'}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kvalitet') }} - {{ $supplier->quality ??  '/' }} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Cena') }} - {{ $supplier->price ??  '/' }} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Rok isporuke') }} - {{ $supplier->shippment_deadline ?? '/' }}
    </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Status') }} - {{$supplier->status ? __('Odobren') : __('Neodobren')}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Datum sledećeg preispitivanja') }} - {{date_format(date_create($supplier->deadline_date),'d.m.Y')}} </p></div>
    <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }} - {{$supplier->user->name}} </p></div>
            
</div>
                                           
<script>window.print()</script>