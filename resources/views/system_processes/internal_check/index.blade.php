<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Godišnji plan internih provera') }}
        </h2>
    </x-slot>

    <div class="row mt-1" id="alert">
        <div class="col">
            @if(Session::has('status'))
                <x-alert :type="Session::get('status')[0]" :message="__(Session::get('status')[1])"/>
            @endif
        </div>
    </div>

    <div class="row mt-3">

        <div class="col">

            <div class="card">

                <div class="card-header">
                    <div class="row">

                        <div class="col-sm-4">
                            @can('create', App\Models\InternalCheck::class)
						        <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('internal-check.create') }}"><i class="fas fa-plus"></i> {{ __('Kreiraj novu internu proveru') }}</a>
					        @endcan
                        </div>

                        <div class="col-sm-8 mt-3 mt-md-0">
                            <form id="formYear" class="form-inline" method="get" action="{{ asset('/internal-check/get-data') }}">
                                <label for="year" class="mr-3 mt-sm-0 mt-2 text-xs sm:text-base">{{ __('Godina') }}</label>
                                <select id="year" class="w-2/3 sm:w-1/4 text-xs sm:text-base mr-2 block border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                    @foreach(range(2019, date("Y")+10) as $year))
                                        <option value="{{ $year }}"@if(session('year')){{ session('year') == $year ? "selected" : "" }}
                                        @else
                                        {{ date('Y') == $year ? "selected" : "" }}
                                        @endif
                                        >{{ $year }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="sm:ml-3 inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white rounded py-2 px-3">{{ __('Primeni') }}</button>
                            </form>
                        </div>

                    </div>
                </div>

                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>{{ __('Termin provere') }}</th>
                                    <th>{{ __('Područje provere') }}</th>
                                    <th>{{ __('Vođe tima i proveravači') }}</th>
                                    <th>{{ __('Standard') }}</th>
                                    <th>{{ __('Br programa IP') }}</th>
                                    <th>{{ __('Izveštaji sa internih provera') }}</th>
                                    <th class="no-sort">{{ __('Akcije') }}</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @forelse($internal_checks as $check)
                                <tr id='trinternalcheck{{$check->id}}'><a id='internalcheck{{$check->id}}'></a>
                                    <td id='tdinternalcheck{{$check->id}}' class="text-center">{{ implode(".",array_reverse(explode("-",$check->date))) }}</td>
                                    <td class="text-center">{{ $check->sector->name }}</td>
                                    <td class="text-center">{{$check->leaders}}</td>
                                    <td class="text-center">{{ $check->standard->name }}</td>
                                    <td class="text-center">
                                        @if(!isset($check->planIp->checked_date))
                                            {{''}}
                                            @can('update', $check)
                                                <a data-toggle="tooltip" data-placement="top" title="{{ __('Kreirajte plan interne provere') }}" href="{{ route('plan-ip.edit',$check->planIp->id) }}"><i class="fas fa-plus"></i></a>
                                            @endcan
                                        @else
                                            <span data-toggle="tooltip" data-placement="top" title="{{ __('Pregled plana interne provere') }}" class="planIpshow" data-url="{{ route('plan-ip.show',$check->planIp->id) }}" style="cursor:pointer;color:blue;">{{ __('PIP') }}  {{$check->planIp->name}}</span>
                                            @can('update', $check)
                                                <a data-toggle="tooltip" data-placement="top" title="{{ __('Izmena plana interne provere') }}" href="{{ route('plan-ip.edit', $check->planIp->id) }}"><i class="fas fa-edit"></i></a>
                                            @endcan
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(!isset($check->internalCheckReport->id))
                                            @if(isset($check->planIp->checked_date))
                                                @can('update', $check)
                                                    <a data-toggle="tooltip" data-placement="top" title="{{ __('Kreirajte izveštaj za internu proveru') }}" href="{{ route('create.report', $check->id) }}"><i class="fas fa-plus"></i></a>
                                                @endcan
                                            @else
                                                @can('update', $check)
                                                    {{ __('Još nije moguće napraviti izveštaj') }}
                                                @endcan
                                            @endif
                                        @else
                                            <span data-toggle="tooltip" data-placement="top" title="{{ __('Pregled izveštaja sa interne provere') }}" class="reportShow" data-url="{{ route('internal-check-report.show', $check->internalCheckReport->id) }}" style="cursor:pointer;color:blue;"><i class="fas fa-eye"></i></span>
                                            @can('update', $check)
                                                <a data-toggle="tooltip" data-placement="top" title="{{ __('Izmena izveštaja sa interne provere') }}" href="{{ route('internal-check-report.edit', $check->internalCheckReport->id) }}"><i class="fas fa-edit"></i></a>
                                                <!-- <form class="inline" action="{{ route('internal-check-report.destroy', $check->internalCheckReport->id) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button class="button" type="submit" style="cursor: pointer; color: red;" onclick="return confirm('Da li ste sigurni?');"><i class="fas fa-trash"></i></button>
                                                </form> -->
                                            @endcan
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @can('update', $check)
                                            <a data-toggle="tooltip" data-placement="top" title="{{ __('Izmena interne provere') }}" href="{{ route('internal-check.edit', $check->id) }}"><i class="fas fa-edit"></i></a>
                                            <form class="inline" id="delete-form-{{ $check->id }}" action="{{ route('internal-check.destroy', $check->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button class="text-red-600 cursor-pointer hover:text-red-800" type="button" data-toggle="tooltip" data-placement="top" title="{{ __('Brisanje interne provere') }}" onclick="confirmDeleteModal({{ $check->id }})"><i class="fas fa-trash"></i></button>
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

    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="px-6 py-4">
                    <div class="text-lg">{{ __('Brisanje interne provere') }}</div>
                    <div class="mt-4">{{ __('Da li ste sigurni?') }}</div>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Odustani') }}</button>
                    <a class="btn-ok hover:no-underline cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150 ml-2">{{ __('Obriši') }}</a>
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

	let href = window.location.href;
	id = href.split('#')[1];
	if(id){
		let e = document.getElementById('tr' + id);
        let i = document.getElementById('td' + id);
        i.innerHTML='<i class="fas fa-hand-point-right"></i> ' +  i.innerHTML;
		e.style = "background:#d8ffcc;";
	}

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
		"order": [[ 0, "asc" ]]
    });

    const planIpShow = document.getElementById('planIp.show');
    const reportShow = document.getElementById('report.show');

    const planIpShowAjax = function(){
        const urlIp = this.dataset.url;
        if (typeof modal !== 'undefined') modal.remove();
        $.ajax({url: urlIp, success: function(result) {
        	const data = JSON.parse(result);
        	const modal = `
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">{{ __('Program broj') }}: ${ data.name }</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Zatvori">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body text-sm">
                            <div class="flex">
                                <div class="flex-1">
                                    <p class="font-bold">{{ __('Termin provere') }}:</p>
                                    <p class="font-bold">{{ __('Sektor') }}:</p>
                                    <p class="font-bold">{{ __('Tim za proveru') }}:</p>
                                    <p class="font-bold">{{ __('Početak provere') }}:</p>
                                    <p class="font-bold">{{ __('Završetak provere') }}:</p>
                                    <p class="font-bold">{{ __('Rok za dostavljanje izveštaja') }}:</p>
                                    <p class="font-bold text-xs">{{ __('Poslednja izmena') }}:</p>
                                </div>
                                <div class="flex-1">
                                    <p>${ new Date(data.checked_date).toLocaleDateString('sr-SR', { timeZone: 'CET' }) }</p>
                                    <p>${ data.checked_sector}</p>
                                    <p>${ data.team_for_internal_check}</p>
                                    <p>${ new Date(data.check_start).toLocaleString('sr-SR', { timeZone: 'CET' }) }</p>
                                    <p>${ new Date(data.check_end).toLocaleString('sr-SR', { timeZone: 'CET' }) }</p>
                                    <p>${new Date(data.report_deadline).toLocaleDateString('sr-SR', { timeZone: 'CET' }) }</p>
                                    <p class="font-italic text-xs">${ new Date(data.updated_at).toLocaleString('sr-SR', { timeZone: 'CET' }) }</p>
                                </div>
                            </div>
						</div>
						<div class="px-6 py-4 bg-gray-100 text-right">
							<button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Zatvori') }}</button>
						</div>
					</div>
				</div>
			`;

      		const m = document.createElement('div');
			m.classList = "modal fade";
			m.tabIndex = "-1";
			m.role = "dialog";
			m.id = "modal";
      		m.innerHTML = modal;
      		let a = document.getElementById('alert');
      		a.append(m);
      		$("#modal").modal('show');
    	}});
    }


    const reportShowAjax = function(){
        const urlReport = this.dataset.url;
        if (typeof modal !== 'undefined') modal.remove();
        $.ajax({url: urlReport, success: function(result) {
			let data = JSON.parse(result);
			data = data[0];
			let modal = `
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">{{ __('Izveštaj sa interne provere') }}</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Zatvori">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<p><h5 class="text-lg">{{ __('Specifikacija') }} </h5>${ data.specification }</p>`;
							let num=1;
							for( let inc of data.corrective_measures){
								modal += `<p class="border-top"><h5 class="text-lg">{{ __('Neusaglašenost') }} ${ num } </h5>${ inc.noncompliance_description }</p>`;
								num++;
							}

								num=1;
							for( let rec of data.recommendations){
								modal += `<p class="border-top"><h5 class="text-lg">{{ __('Preporuka') }} ${ num } </h5>${ rec.description }</p>`;
								num++;
							}
							modal+=`
							<small> <b>{{ __('Kreirano') }}:</b> <i>${ new Date(data.created_at).toLocaleString('sr-SR',{ timeZone: 'CET' }) }</i> </small><br>
                            <small> <b>{{ __('Poslednja izmena') }}:</b> <i>${ new Date(data.updated_at).toLocaleString('sr-SR',{ timeZone: 'CET' }) }</i> </small>
						</div>
						<div class="px-6 py-4 bg-gray-100 text-right">
							<button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Zatvori') }}</button>
						</div>
					</div>
				</div>
			`;

			const m = document.createElement('div');
			m.classList = "modal fade";
			m.tabIndex = "-1";
			m.role = "dialog";
			m.id = "modal";
			m.innerHTML = modal;
			let a = document.getElementById('alert');
			a.append(m);
			$("#modal").modal('show');
    	}});
    }

    for(plan of document.querySelectorAll('.planIpshow')){
    	plan.addEventListener('click', planIpShowAjax);
    }

    for(report of document.querySelectorAll('.reportShow')){
    	report.addEventListener('click', reportShowAjax);
    }

    document.getElementById('year').addEventListener('change', function(){
        document.getElementById('formYear').action = '/internal-check/get-data/' + this.value;
    });

    function confirmDeleteModal($id){
        let id = $id;
        $('#confirm-delete-modal').modal();
        $('#confirm-delete-modal').on('click', '.btn-ok', function(e) {
            let form = $('#delete-form-'+id);
            form.submit();
        });
    }

    $('[data-toggle="tooltip"]').tooltip();

</script>
