<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Merna oprema') }}
        </h2>
    </x-slot>

    <div class="row mt-1">
        <div class="col">
            @if(Session::has('status'))
                <div class="alert alert-info alert-dismissible fade show">
                    {{ Session::get('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(Session::has('warning'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ Session::get('warning') }}
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
                @can('create', App\Models\MeasuringEquipment::class)
                <div class="card-header">
                    <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('measuring-equipment.create') }}"><i class="fas fa-plus"></i> Kreiraj mernu opremu</a>
                </div>
                @endcan
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>Oznaka merne opreme</th>
                                    <th>Naziv merne opreme</th>
                                    <th>Datum poslednjeg etaloniranja/bandažiranja</th>
                                    <th>Datum narednog etaloniranja/bandažiranja</th>
                                    <th class="no-sort">Akcije</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($measuring_equipment as $me)
                                <tr id='trmeasuringequipment{{$me->id}}'><a id='measuringequipment{{$me->id}}'></a>
                                    <td class="text-center">{{ $me->label }}</td>
                                    <td class="text-center">{{ $me->name }}</td>
                                    <td class="text-center">@if($me->last_calibration_date){{ date('d.m.Y', strtotime($me->last_calibration_date)) }}@else {{"/"}}@endif</td>
                                    <td class="text-center">@if($me->next_calibration_date){{ date('d.m.Y', strtotime($me->next_calibration_date)) }}@else {{"/"}}@endif</td>
                                    @canany(['update', 'delete'], $me)
                                    <td class="text-center">
                                        <a data-toggle="tooltip" data-placement="top" title="Izmena merne opreme" href="{{ route('measuring-equipment.edit', $me->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" action="{{ route('measuring-equipment.destroy', $me->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button data-toggle="tooltip" data-placement="top" title="Brisanje merne opreme" class="button text-danger" type="submit" style="cursor: pointer;" onclick="return confirm('Da li ste sigurni?');"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                    @endcanany
                                </tr>   
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>  

        </div>

    </div>

</x-app-layout>

<script>
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

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });

    var myRe = /\bmeasuring-equipment\b/g;
  	if(myRe.test(window.location.href)){
    	window.addEventListener('popstate', function (event) {
    		location.reload();
    	});
  	}

	let href = window.location.href;
	id = href.split('#')[1];
	if(id){
		let e = document.getElementById('tr' + id);
		e.style = "background:#d8ffcc;";
	}
</script>
