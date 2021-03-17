<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Merna oprema') }}
        </h2>
    </x-slot>

    <div class="row mt-1">
        <div class="col">
            @if(Session::has('status'))
                <x-alert :type="Session::get('status')[0]" :message="Session::get('status')[1]"/>
            @endif
        </div>
    </div>

    <div class="row mt-3">

        <div class="col">

            <div class="card">
                @can('create', App\Models\MeasuringEquipment::class)
                <div class="card-header">
                    <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('measuring-equipment.create') }}"><i class="fas fa-plus"></i> {{ __('Kreiraj mernu opremu')}}</a>
                    <div class="col-sm-4 float-right">
                        <a id="excelBtn" class="inline-block float-right text-xs md:text-base bg-green-500 hover:bg-green-700 text-white hover:no-underline rounded py-2 px-3" href="{{ '/measuring-equipment-export' }}"><i class="fas fa-file-export"></i> {{ __('Excel') }}</a>
                    </div>
                </div>
                @endcan
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>{{__('Oznaka merne opreme')}}</th>
                                    <th>{{__('Naziv merne opreme')}}</th>
                                    <th>{{__('Datum poslednjeg etaloniranja/baždarenja')}}</th>
                                    <th>{{__('Datum narednog etaloniranja/baždarenja')}}</th>
                                    <th>{{__('Kreirao')}}</th>
                                    <th class="no-sort">{{__('Akcije')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($measuring_equipment as $me)
                                <tr id='trmeasuringequipment{{$me->id}}'><a id='measuringequipment{{$me->id}}'></a>
                                    <td id='tdmeasuringequipment{{$me->id}}' class="text-center">{{ $me->label }}</td>
                                    <td class="text-center">{{ $me->name }}</td>
                                    <td class="text-center">@if($me->last_calibration_date){{ date('d.m.Y', strtotime($me->last_calibration_date)) }}@else {{"/"}}@endif</td>
                                    <td class="text-center">@if($me->next_calibration_date){{ date('d.m.Y', strtotime($me->next_calibration_date)) }}@else {{"/"}}@endif</td>
                                    <td class="text-center">{{ $me->user->name }}</td>
                                    <td class="text-center">
                                        @canany(['update', 'delete'], $me)
                                        <a data-toggle="tooltip" data-placement="top" title="{{__('Izmena merne opreme')}}" href="{{ route('measuring-equipment.edit', $me->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" id="delete-form-{{ $me->id }}" action="{{ route('measuring-equipment.destroy', $me->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button data-toggle="tooltip" data-placement="top" title="{{__('Brisanje merne opreme')}}" class="text-red-600 cursor-pointer hover:text-red-800" type="button" onclick="confirmDeleteModal({{ $me->id }})"><i class="fas fa-trash"></i></button>
                                        </form>
                                        @endcanany
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="px-6 py-4">
                    <div class="text-lg">{{__('Brisanje merne opreme')}}</div>
                    <div class="mt-4">{{__('Da li ste sigurni?')}}</div>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{__('Odustani')}}</button>
                    <a class="btn-ok hover:no-underline cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150 ml-2">{{__('Obriši')}}</a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<script>
    $('.yajra-datatable').DataTable({
        "language": {
            "info": "{{__('Prikaz strane')}} _PAGE_ {{__('od')}} _PAGES_",
            "infoEmpty": "{{__('Nema podataka')}}",
            "zeroRecords": "{{__('Nema podataka')}}",
            "infoFiltered": "({{__('od')}} _MAX_ {{__('ukupno rezultata')}})",
            "lengthMenu": "{{__('Prikaži')}} _MENU_ {{__('redova po stranici')}}",
            "search": "{{__('Pretraga')}}",
            "paginate": {
                "next": "{{__('Sledeća')}}",
                "previous": "{{__('Prethodna')}}",
                "first": "{{__('Prva')}}",
                "last": "{{__('Poslednja')}}"
            }
        },
        "columnDefs": [{
          "targets": 'no-sort',
          "orderable": false,
        }],
        "order": [[ 3, "asc" ]]
    });

    $('[data-toggle="tooltip"]').tooltip();

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
        let i = document.getElementById('td' + id);
        i.innerHTML = '<i class="fas fa-hand-point-right"></i> ' +  i.innerHTML;
		e.style = "background:#d8ffcc;";
    }

    function confirmDeleteModal($id){
        let id = $id;
        $('#confirm-delete-modal').modal();
        $('#confirm-delete-modal').on('click', '.btn-ok', function(e) {
            let form = $('#delete-form-'+id);
            form.submit();
        });
    }
</script>
