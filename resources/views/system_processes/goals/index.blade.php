<x-app-layout>
    @push('scripts')
    <!-- Datatable -->
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.25/sorting/date-de.js"></script>
    @endpush

    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
                {{ session('standard_name') }} - {{ __('Ciljevi') }}
            </h2>
            @include('includes.video')
        </div>
    </x-slot>

    <div class="row mt-1">
        <div class="col">
            @if(Session::has('status'))
            <x-alert :type="Session::get('status')[0]" :message="__(Session::get('status')[1])" />
            @endif
        </div>
    </div>

    <div class="row mt-3">

        <div class="col">

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-4">
                            @can('create', App\Models\Goal::class)
                            <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3"
                                href="{{ route('goals.create') }}"><i class="fas fa-plus"></i>
                                {{ __('Kreiraj novi cilj') }}</a>
                            @endcan
                        </div>
                        <div class="col-sm-4">
                            <form class="form-inline">
                                <label for="goals-year" class="mr-3 text-xs md:text-base">{{ __('Godina') }}</label>
                                <select name="goals-year" id="goals-year" onchange="excelYear()"
                                    class="appearance-none w-2/3 sm:w-2/4 text-xs sm:text-base mr-2 block border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                    <option value="all">{{ __('Sve godine') }}</option>
                                    @foreach(range(2020, date("Y")+10) as $year))
                                    <option value="{{ $year }}" {{ date('Y') == $year ? "selected" : "" }}>{{ $year }}
                                    </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="col-sm-4 float-right">
                            <a id="excelBtn"
                                class="inline-block float-right text-xs md:text-base bg-green-500 hover:bg-green-700 text-white hover:no-underline rounded py-2 px-3"
                                href="{{ '/goals-export' }}"><i class="fas fa-file-export"></i> {{ __('Excel') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>{{ __('Godina') }}</th>
                                    <th>{{ __('Nivo važnosti') }}</th>
                                    <th>{{ __('Cilj') }}</th>
                                    <th>{{ __('KPI') }}</th>
                                    <th>{{ __('Potrebne aktivnosti za realizaciju cilja') }}</th>
                                    <th>{{ __('Odgovornost za praćenje i realizaciju cilja') }}</th>
                                    <th>{{ __('Rok za realizaciju cilja') }}</th>
                                    <th>{{ __('Potrebni resursi') }}</th>
                                   <!-- <th>{{ __('Analiza ispunjenosti cilja') }}</th> -->
                                    <th class="no-sort w-20">{{ __('Akcije') }}</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach($goals as $goal)
                                <tr id='trgoal{{$goal->id}}'><a id='goal{{$goal->id}}'></a>
                                    <td id='tdgoal{{$goal->id}}' class="text-center">{{ $goal->year }}</td>
                                    <td class="text-center">{{ $goal->levelIs() }}</td>
                                    <td class="text-center">
                                        {{ Str::length($goal->goal) < 35 ? $goal->goal : Str::limit($goal->goal, 35) }}
                                    </td>
                                    <td class="text-center">
                                        {{ Str::length($goal->kpi) < 35 ? $goal->kpi : Str::limit($goal->kpi, 35) }}
                                    </td>
                                    <td class="text-center">
                                        {{ Str::length($goal->activities) < 35 ? $goal->activities : Str::limit($goal->activities, 35) }}
                                    </td>
                                    <td class="text-center">
                                        {{ $goal->users ? $goal->users()->pluck('name')->implode(', ') : '/' }}</td>
                                    <td class="text-center">{{ date('d.m.Y', strtotime($goal->deadline)) }}</td>
                                    <td class="text-center">{{ $goal->resources }}</td>
                                   <!-- <td class="text-center">{{ Str::limit($goal->analysis, 35) ? : '/' }}</td> -->
                                    <td class="text-center">
                                        <button data-toggle="tooltip" data-placement="top"
                                            title="{{ __('Pregled cilja') }}" class="text-blue-700 hover:text-blue-900"
                                            onclick="showGoal({{ $goal->id }})"><i class="fas fa-eye"></i></button>
                                        <a href="{{route('goals.print',$goal->id)}}" target="_blank"
                                            data-toggle="tooltip" data-placement="top"
                                            class="text-green-400 hover:text-green-600" title="{{__('Odštampaj')}}"><i
                                                class="fas fa-print"></i>
                                        </a>
                                        @canany(['update', 'delete'], $goal)
                                        <a data-toggle="tooltip" data-placement="top" title="{{ __('Izmena cilja') }}"
                                            href="{{ route('goals.edit', $goal->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" id="delete-form-{{ $goal->id }}"
                                            action="{{ route('goals.destroy', $goal->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="text-red-600 cursor-pointer hover:text-red-800" type="button"
                                                data-toggle="tooltip" data-placement="top"
                                                title="{{ __('Brisanje cilja') }}"
                                                onclick="confirmDeleteModal({{ $goal->id }})"><i
                                                    class="fas fa-trash"></i></button>
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

    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="px-6 py-4">
                    <div class="text-lg">{{ __('Brisanje cilja') }}</div>
                    <div class="mt-4">{{ __('Da li ste sigurni?') }}</div>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <button type="button"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150"
                        data-dismiss="modal">{{ __('Odustani') }}</button>
                    <a
                        class="btn-ok hover:no-underline cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150 ml-2">{{ __('Obriši') }}</a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<script>
    var myRe = /\bgoals\b/g;

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
        "columnDefs": [
            {
            "targets": 'no-sort',
            "orderable": false,
            },
            {
                "type": 'de_date',
                targets: 6
            }
        ],
        "aaSorting": []
    });

    function showGoal(id){
        axios.get('/goals/'+id)
            .then((response) => {
                let modal = `
                    <div class="modal fade" id="showData-${ id }" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content rounded-0">
                                <div class="modal-header text-center">
                                    <h5 class="modal-title font-weight-bold">${ response.data.goal }</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-sm">
                                    <div class="row">
                                        <div class="col-sm-5 mt-1 border-bottom font-weight-bold"><p>{{ __('Godina') }}</p></div>
                                        <div class="col-sm-7 mt-1 border-bottom"><p>${ response.data.year }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Nivo važnosti') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.level }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Rok za realizaciju cilja') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ new Date(response.data.deadline).toLocaleDateString('sr-SR', { timeZone: 'CET' }) }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Odgovornost za praćenje i realizaciju cilja') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>`;
                                        $.each(response.data.users, function (j, user){
                                            modal += user.name;
                                            if(j != response.data.users.length-1){
                                                modal += ", ";
                                            }
                                        });
                                        modal += `</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Resursi') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.resources }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('KPI') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.kpi }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Aktivnosti') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.activities }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Da li je cilj ispunjen') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.status == 1 ? "{{ __('Da') }}":"{{ __('Ne') }}" }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Analiza') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.analysis != null ? response.data.analysis : "/" }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.user.name  }</p></div>
                                    </div>
                                </div>
                                <div class="px-6 py-4 bg-gray-100 text-right">
                                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Zatvori') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $("body").append(modal);
                $('#showData-'+id).modal();
            })
            .catch((error) => {
                console.log(error)
            })
    }

    function deleteGoal(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let url = "/goals/delete/"+id;

        if(confirm('Da li ste sigurni?')){
            $.ajax({
                type: "delete",
                url: url,
                data: {
                    id: id
                },
                success: function(result) {
                    alert('Cilj uspešno uklonjen');
                    location.reload();
                },
                error: function(result) {
                    alert('error', result);
                }
            });
        }
    }

    $('#goals-year').change( () => {
        let year = $('#goals-year').val();
        const data = {'year': year}

        axios.post('/goals/get-data', { data })
        .then((response) => {
            if(response.data.length == 0){
                $('#table-body').html('<td colspan="10" class="dataTables_empty" valign="top">{{ __("Nema podataka") }}</td>');
            }
            else{
                let allData = "";
                $.each(response.data, function (i, item){
                    let row = `
                        <tr>
                            <td class="text-center">${ item.year }</td>
                            <td class="text-center">${ item.level }</td>
                            <td class="text-center">${ item.goal.length < 35 ? item.goal : item.goal.substr(0, 35) + "..."}</td>
                            <td class="text-center">${ item.kpi.length < 35 ? item.kpi : item.kpi.substr(0, 35) + "..."}</td>
                            <td class="text-center">${ item.activities.length < 35 ? item.activities : item.activities.substr(0, 35) + "..."}</td>
                            <td class="text-center">`;
                            $.each(item.users, function (j, user){
                                row += user.name;
                                if(j != item.users.length-1){
                                    row += ", ";
                                }
                            });
                            row += `</td>
                            <td class="text-center">${ new Date(item.deadline).toLocaleDateString('sr-SR', { timeZone: 'CET' }) }</td>
                            <td class="text-center">${ item.resources }</td>
                            <td class="text-center">${ item.analysis != null ? item.analysis.substr(0, 35) + "..." : "/" }</td>
                            <td class="text-center">
                                <button data-toggle="tooltip" data-placement="top" title="{{ __('Pregled cilja') }}" class="text-blue-700 hover:text-blue-900" onclick="showGoal(${ item.id })"><i class="fas fa-eye"></i></button>
                                <a href="/goals/print/${ item.id }" target="_blank" data-toggle="tooltip" data-placement="top" class="text-green-400 hover:text-green-600" title="{{__('Odštampaj')}}" ><i class="fas fa-print"></i></a>
                                <span class="${ item.isAdmin === false ? 'd-none' : '' }">
                                    <a data-toggle="tooltip" data-placement="top" title="{{ __('Izmena cilja') }}" href="/goals/${ item.id }/edit"><i class="fas fa-edit"></i></a>
                                    <a data-toggle="tooltip" data-placement="top" title="{{ __('Brisanje cilja') }}" class="text-red-600 cursor-pointer hover:text-red-700" id="delete-goal" onclick="deleteGoal(${ item.id })" data-id="${ item.id }"><i class="fas fa-trash"></i></a>
                                </span>
                            </td>
                        </tr>
                    `;
                    allData += row;
                });
                $('[data-toggle="tooltip"]').tooltip();
                $('#table-body').html(allData)
            }
        }, (error) => {
            console.log(error);
        })
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

    function excelYear(){
        document.getElementById('excelBtn').href = '/goals-export?year='+document.getElementById('goals-year').value;
   }


</script>
