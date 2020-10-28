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
        </div>
    </div>

    <div class="row mt-3">

        <div class="col">

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-4">
                            @can('create', App\Models\Goal::class)
                                <a class="btn btn-info" href="{{ route('goals.create') }}"><i class="fas fa-plus"></i> Kreiraj novi cilj</a>
                            @endcan
                        </div>
                        <div class="col-sm-8">
                            <form class="form-inline">
                                <label for="goals-year" class="mr-3">Godina</label>
                                <select name="goals-year" id="goals-year" class="form-control w-25 mr-2">
                                    @foreach(range(date("Y")-1, date("Y")+10) as $year))
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
                                <tr>
                                    <td class="text-center">{{ $goal->year }}</td>
                                    <td class="text-center">{{ $goal->goal }}</td>
                                    <td class="text-center">{{ $goal->kpi }}</td>
                                    <td class="text-center">{{ $goal->activities }}</td>
                                    <td class="text-center">{{ $goal->responsibility }}</td>
                                    <td class="text-center">{{ date('d.m.Y', strtotime($goal->deadline)) }}</td>
                                    <td class="text-center">{{ $goal->resources }}</td>
                                    <td class="text-center">{{ $goal->analysis ? : '/' }}</td>
                                    <td class="text-center">
                                        <button class="button text-primary" onclick="showGoal({{ $goal->id }})"><i class="fas fa-eye"></i></button>
                                        @canany(['update', 'delete'], $goal)
                                        <a href="{{ route('goals.edit', $goal->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" action="{{ route('goals.destroy', $goal->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="button text-danger" type="submit" style="cursor: pointer;" onclick="return confirm('Da li ste sigurni?');"><i class="fas fa-trash"></i></button>
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

    function showGoal(id){
        axios.get('/goals/'+id)
            .then((response) => {
                let modal = `<div class="modal" id="showData" tabindex="-1" role="dialog">
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
                                                <div class="col-sm-5 mt-1 border-bottom font-weight-bold"><p>Rok za realizaciju</p></div>
                                                <div class="col-sm-7 mt-1 border-bottom"><p>${ new Date(response.data.deadline).toLocaleDateString('sr-SR', { timeZone: 'CET' }) }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Godina</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.year }</p></div>
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
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Zatvori</button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                $("body").append(modal);
                $('#showData').modal();
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
                            <td class="text-center">${ item.activities }</td>
                            <td class="text-center">${ item.responsibility }</td>
                            <td class="text-center">${ new Date(item.deadline).getUTCDate() + '.' + new Date(item.deadline).getUTCMonth() + '.' + new Date(item.deadline).getUTCFullYear() }</td>
                            <td class="text-center">${ item.resources }</td>
                            <td class="text-center">${ item.analysis != null ? item.analysis : "/" }</td>
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

</script>
