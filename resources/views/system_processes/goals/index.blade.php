<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Ciljevi') }}
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
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-4">
                            @can('create', App\Models\Goal::class)
                                <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('goals.create') }}"><i class="fas fa-plus"></i> Kreiraj novi cilj</a>
                            @endcan
                        </div>
                        <div class="col-sm-8">
                            <form class="form-inline">
                                <label for="goals-year" class="mr-3 text-xs md:text-base mt-2 sm:mt-0">Godina</label>
                                <select name="goals-year" id="goals-year" class="w-2/3 sm:w-1/4 text-xs sm:text-base mt-2 sm:mt-0 mr-2 block border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                    @foreach(range(2019, date("Y")+10) as $year))
                                        <option value="{{ $year }}" {{ date('Y') == $year ? "selected" : "" }} >{{ $year }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>Godina</th>
                                    <th>Cilj</th>
                                    <th>KPI</th>
                                    <th>Potrebne aktivnosti za realizaciju cilja</th>
                                    <th>Odgovornost za praćenje i realizaciju cilja</th>
                                    <th>Rok za realizaciju</th>
                                    <th>Potrebni resursi</th>
                                    <th>Analiza ispunjenosti cilja</th>
                                    <th class="no-sort">Akcije</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                            @foreach($goals as $goal)
                                <tr id='trgoal{{$goal->id}}'><a id='goal{{$goal->id}}'></a>
                                    <td id='tdgoal{{$goal->id}}' class="text-center">{{ $goal->year }}</td>
                                    <td class="text-center">{{ $goal->goal }}</td>
                                    <td class="text-center">{{ $goal->kpi }}</td>
                                    <td class="text-center">{{ Str::length($goal->activities) < 35 ? $goal->activities : Str::limit($goal->activities, 35) }}</td>
                                    <td class="text-center">{{ $goal->responsibility }}</td>
                                    <td class="text-center">{{ date('d.m.Y', strtotime($goal->deadline)) }}</td>
                                    <td class="text-center">{{ $goal->resources }}</td>
                                    <td class="text-center">{{ Str::limit($goal->analysis, 35) ? : '/' }}</td>
                                    <td class="text-center">
                                        <button data-toggle="tooltip" data-placement="top" title="Pregled cilja" class="button text-primary" onclick="showGoal({{ $goal->id }})"><i class="fas fa-eye"></i></button>
                                        @canany(['update', 'delete'], $goal)
                                        <a data-toggle="tooltip" data-placement="top" title="Izmena cilja" href="{{ route('goals.edit', $goal->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" id="delete-form-{{ $goal->id }}" action="{{ route('goals.destroy', $goal->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="text-red-600 cursor-pointer hover:text-red-800" type="button" data-toggle="tooltip" data-placement="top" title="Brisanje cilja" onclick="confirmDeleteModal({{ $goal->id }})"><i class="fas fa-trash"></i></button>
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
                    <div class="text-lg">Brisanje cilja</div>
                    <div class="mt-4">Da li ste sigurni?</div>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">Odustani</button>
                    <a class="btn-ok hover:no-underline cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150 ml-2">Obriši</a>
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
	id=href.split('#')[1];
	if(id){
		let e = document.getElementById('tr' + id);
        let i = document.getElementById('td' + id);
        i.innerHTML='<i class="fas fa-hand-point-right"></i> ' +  i.innerHTML;
		e.style = "background:#d8ffcc;";
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
        "order": [[ 6, "desc" ]]
    });

    function showGoal(id){
        axios.get('/goals/'+id)
            .then((response) => {
                let modal = `<div class="modal fade" id="showData-${ id }" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content rounded-0">
                                        <div class="modal-header text-center">
                                            <h5 class="modal-title font-weight-bold">${ response.data.goal }</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-5 mt-1 border-bottom font-weight-bold"><p>Godina</p></div>
                                                <div class="col-sm-7 mt-1 border-bottom"><p>${ response.data.year }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Rok za realizaciju</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ new Date(response.data.deadline).toLocaleDateString('sr-SR', { timeZone: 'CET' }) }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Odgovornost</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.responsibility }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Resursi</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.resources }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>KPI</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.kpi }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Aktivnosti</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.activities }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Analzia</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.analysis != null ? response.data.analysis : "/" }</p></div>
                                            </div>
                                        </div>
                                        <div class="px-6 py-4 bg-gray-100 text-right">
                                            <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">Zatvori</button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
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
                $('#table-body').html('<td colspan="10" class="dataTables_empty" valign="top">Nema podataka</td>');
            }
            else{
                let allData = "";
                $.each(response.data, function (i, item){
                    let row = `<tr>
                            <td class="text-center">${ item.year }</td>
                            <td class="text-center">${ item.goal }</td>
                            <td class="text-center">${ item.kpi }</td>
                            <td class="text-center">${ item.activities.length < 35 ? item.activities : item.activities.substr(0, 35) + "..."}</td>
                            <td class="text-center">${ item.responsibility }</td>
                            <td class="text-center">${ new Date(item.deadline).getUTCDate() + '.' + new Date(item.deadline).getUTCMonth() + '.' + new Date(item.deadline).getUTCFullYear() }</td>
                            <td class="text-center">${ item.resources }</td>
                            <td class="text-center">${ item.analysis != null ? item.analysis.substr(0, 35) + "..." : "/" }</td>
                            <td class="text-center">
                                <button class="button text-primary" onclick="showGoal(${ item.id })"><i class="fas fa-eye"></i></button>
                                <span class="${ item.isAdmin === false ? 'd-none' : '' }">
                                    <a href="/goals/${ item.id }/edit"><i class="fas fa-edit"></i></a>
                                    <a style="cursor: pointer; color: red;" id="delete-goal" onclick="deleteGoal(${ item.id })" data-id="${ item.id }"><i class="fas fa-trash"></i></a>
                                </span>
                            </td>
                            </tr>`;
                    allData += row;
                });
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

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>
