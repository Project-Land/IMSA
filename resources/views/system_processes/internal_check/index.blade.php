<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Godišnji planovi internih provera') }}
           
        </h2>
    </x-slot>

    <div class="row mt-1" id="alert">
        <div class="col">
            @if(Session::has('status'))
                <div class="alert alert-info alert-dismissible fade show">
                    {{ Session::get('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="row mt-3">

        <div class="col">

            <div class="card">
                <div class="card-header">
                @can('create', App\Models\InternalCheck::class)
                    <a class="btn btn-info" href="{{ route('internal-check.create') }}"><i class="fas fa-plus"></i> Kreiraj novi godišnji plan</a>
                @endcan
                </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>Termin provere</th>
                                    <th>Područije provere</th>
                                    <th>Vođe tima i proveravači</th>
                                    <th>Standard</th>
                                    <th>Br program ip</th>
                                    <th>Izveštaji sa internih provera</th>
                                    <th class="no-sort">Akcije</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($internal_checks as $check)
                            <tr id='trinternalcheck{{$check->id}}'><a id='internalcheck{{$check->id}}'></a>
                                    <td>{{ implode("/",array_reverse(explode("-",$check->date))) }}</td>
                                    <td>{{ $check->sector->name }}</td>
                                    <td>{{ $check->leaders }}</td>
                                    <td>{{ $check->standard->name }}</td>
                                    <td class="text-center">
                                    @if(!isset($check->planIp->checked_date))
                                       {{''}}
                                     @can('update', $check)   
                                       <a href="{{ route('plan-ip.edit',$check->planIp->id) }}"><i class="fas fa-plus"></i></a>
                                     @endcan

                                    @else
                                    <span class="planIpshow" data-url="{{ route('plan-ip.show',$check->planIp->id) }}" style="cursor:pointer;color:blue;">{{'PIP'}}  {{$check->planIp->name}}</span> 
                                    @can('update', $check)
                                      <a href="{{ route('plan-ip.edit', $check->planIp->id) }}"><i class="fas fa-edit"></i></a>
                                      <form class="inline" action="{{ route('plan-ip.destroy', $check->id) }}" method="POST">
                                              @method('DELETE')
                                              @csrf
                                              <button class="button" type="submit" style="cursor: pointer; color: red;" onclick="return confirm('Da li ste sigurni?');"><i class="fas fa-trash"></i></button>
                                      </form>
                                      @endcan
                                    @endif
                                   
                            
                                    </td>
                                  
                                    <td class="text-center">
                                    
                                    @if(!isset($check->internalCheckReport->id))
                                    @if(isset($check->planIp->checked_date))
                                    @can('update', $check)
                                    <a href="{{ route('create.report', $check->id) }}"><i class="fas fa-plus"></i></a>
                                    @endcan
                                    @else
                                    @can('update', $check)
                                    {{'Još nije moguće napraviti izveštaj'}}
                                    @endcan
                                    @endif
                                   
                                   
                                    @else
                                    <span class="reportShow" data-url="{{ route('internal-check-report.show', $check->internalCheckReport->id) }}" style="cursor:pointer;color:blue;"><i class="fas fa-eye"></i></span>
                                    @can('update', $check)
                                    <a href="{{ route('internal-check-report.edit', $check->internalCheckReport->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" action="{{ route('internal-check-report.destroy', $check->internalCheckReport->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="button" type="submit" style="cursor: pointer; color: red;" onclick="return confirm('Da li ste sigurni?');"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @endcan
                                    </td>
                                    @endif

                                    
                                  
    

                                    <td class="text-center">
                                    @can('update', $check)
                                        <a href="{{ route('internal-check.edit', $check->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" action="{{ route('internal-check.destroy', $check->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="button" type="submit" style="cursor: pointer; color: red;" onclick="return confirm('Da li ste sigurni?');"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @endcan
                                    </td>
                                    
                                </tr> 
                                @empty  
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>  

        </div>

    </div>

    




</x-app-layout>

<script>
  var myRe = /\binternal-check\b/g;
  if(myRe.test(window.location.href)){
    window.addEventListener('popstate', function (event) {
    location.reload();
    });
  }

let href=window.location.href;
id=href.split('#')[1];
if(id){
 let e= document.getElementById('tr'+id);
 e.style="background:#bbfca9;";
}


    $('.yajra-datatable').DataTable({
        "language": {
            "info": "Prikaz strane _PAGE_ od _PAGES_",
            "infoEmpty": "Nema podataka",
            "zeroRecords": "Nema podataka",
            "infoFiltered": "(od _MAX_ ukupno rezultata)",
            "lengthMenu": "Prikaži _MENU_ redova po stranici",
            "search": "Pretraga",
            "paginate": {
                "next": "Sledeća",
                "previous": "Prethodna",
                "first": "Prva",
                "last": "Poslednja"
            }
        },
        "columnDefs": [{
          "targets": 'no-sort',
          "orderable": false,
        }],
    }); 



    const planIpShow=document.getElementById('planIp.show');
    const reportShow=document.getElementById('report.show');
    
    
    
    
   

    const planIpShowAjax=function(){
        const urlIp=this.dataset.url;
        if (typeof modal !== 'undefined') modal.remove();
        $.ajax({url: urlIp, success: function(result){
        const data=JSON.parse(result);
        const modal=`
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Program broj: ${data.name}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Zatvori">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Termin provere: ${new Date(data.checked_date).toLocaleString('sr-SR',{ timeZone: 'CET' })}</p>
        <p>Sektor: ${data.checked_sector}</p>
        <p>Tim za proveru: ${data.team_for_internal_check}</p>
        <p>Početak provere: ${new Date(data.check_start).toLocaleString('sr-SR',{ timeZone: 'CET' })}</p>
        <p>Završetak provere: ${new Date(data.check_end).toLocaleString('sr-SR',{ timeZone: 'CET' })}</p>
        <p>Rok za dostavljanje izveštaja: ${new Date(data.report_deadline).toLocaleString('sr-SR',{ timeZone: 'CET' })} </p>
        <small> kreirano: ${new Date(data.created_at).toLocaleString('sr-SR',{ timeZone: 'CET' })} </small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zatvori</button>
      </div>
    </div>
  </div>
`;
const m=document.createElement('div');
m.classList="modal";
m.tabIndex="-1";
m.role="dialog";
m.id="modal";
m.innerHTML=modal;
let a=document.getElementById('alert');
a.append(m);
$("#modal").modal('show');

    }});
    }


    const reportShowAjax=function(){
        const urlReport=this.dataset.url;
        if (typeof modal !== 'undefined') modal.remove();
        $.ajax({url: urlReport, success: function(result){
        let data=JSON.parse(result);
        data=data[0];
        let modal=`
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Izveštaj sa interne provere</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Zatvori">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <p><h2>Specifikacija: </h2>${data.specification}</p>`;
      let num=1;
      for( let inc of data.inconsistencies){
        modal+=`<p><h3>Neusaglašenost ${num}: </h3>${inc.description}</p>`;
        num++;
      }

        num=1;
      for( let rec of data.recommendations){
        modal+=`<p><h3>Preporuka ${num}: </h3>${rec.description}</p>`;
        num++;
      }
       modal+=`
        <small> kreirano: ${new Date(data.created_at).toLocaleString('sr-SR',{ timeZone: 'CET' })} </small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zatvori</button>
      </div>
    </div>
  </div>
`;
const m=document.createElement('div');
m.classList="modal";
m.tabIndex="-1";
m.role="dialog";
m.id="modal";
m.innerHTML=modal;
let a=document.getElementById('alert');
a.append(m);
$("#modal").modal('show');

    }});
    }


    for(plan of document.querySelectorAll('.planIpshow')){
    plan.addEventListener('click',planIpShowAjax);
    }

    for(report of document.querySelectorAll('.reportShow')){
    report.addEventListener('click',reportShowAjax);
    }
   
</script>