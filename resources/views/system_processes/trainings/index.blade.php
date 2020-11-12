<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Godišnji planovi obuke') }}
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
                            @can('create', App\Models\Training::class)
                            <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('trainings.create') }}"><i class="fas fa-plus"></i> Dodaj obuku</a>
                            @endcan
                        </div>
                        <div class="col-sm-8">
                            <form class="form-inline">
                                <label for="year" class="mr-3">Godina</label>
                                <select name="year" id="trainings-year" class="form-control w-25 mr-2">
                                    @foreach(range(date('Y')-1, date('Y')+10) as $year)
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
                                    <th>Naziv</th>
                                    <th>Vrsta</th>
                                    <th>Opis</th>
                                    <th>Br. zaposlenih</th>
                                    <th>Termin / Mesto</th>
                                    <th>Resursi</th>
                                    <th>Br. zaposlenih - realizovano</th>
                                    <th>Ocena efekata obuke</th>
                                    <th class="no-sort">Akcije</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach($trainingPlans as $tp)
                                <tr>
                                    <td class="text-center">{{ $tp->year }}</td>
                                    <td class="text-center">{{ $tp->name }}</td>
                                    <td class="text-center">{{ $tp->type }}</td>
                                    <td class="text-center">{{ Str::length($tp->description) < 35 ? $tp->description : Str::limit($tp->description, 35) }}</td>
                                    <td class="text-center">{{ $tp->num_of_employees }}</td>
                                    <td class="text-center">{{ date('d.m.Y', strtotime($tp->training_date)) }} u {{ date('H:i', strtotime($tp->training_date)) }}, {{ $tp->place }}</td>
                                    <td class="text-center">{{ Str::length($tp->resources) < 35 ? $tp->resources : Str::limit($tp->resources, 35) }}</td>
                                    <td class="text-center">{{ $tp->final_num_of_employees? : '/' }}</td>
                                    <td class="text-center">{{ $tp->rating? : '/' }}</td>
                                    <td class="text-center">
                                        @canany(['update', 'delete'], $tp)
                                        <a data-toggle="tooltip" data-placement="top" title="Izmena obuke" href="{{ route('trainings.edit', $tp->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" action="{{ route('trainings.destroy', $tp->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button data-toggle="tooltip" data-placement="top" title="Brisanje obuke" class="button text-danger" type="submit" style="cursor: pointer;" onclick="return confirm('Da li ste sigurni?');"><i class="fas fa-trash"></i></button>
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
        "order": [[ 6, "desc" ]]
    });

    function deleteTraining(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let url = "/trainings/delete/"+id;
        
        if(confirm('Da li ste sigurni?')){
            $.ajax({
                type: "delete",
                url: url,
                data: { 
                    id: id
                },
                success: function(result) {
                    alert('Godišnji plan obuke uspešno uklonjen');
                    location.reload();
                },
                error: function(result) {
                    alert('error', result);
                }
            });
        }
    }

    $('#trainings-year').change( () => {
        let year = $('#trainings-year').val();
        const data = {'year': year}
        axios.post('/trainings/get-data', { data })
        .then((response) => {
            if(response.data.length == 0){
                $('#table-body').html('<td colspan="10" class="dataTables_empty" valign="top">Nema podataka</td>');
            }
            else{
                let allData = "";
                $.each(response.data, function (i, item){
                    let row = `<tr>
                                <td class="text-center">${ item.year }</td>
                                <td class="text-center">${ item.name }</td>
                                <td class="text-center">${ item.type }</td>
                                <td class="text-center">${ item.description.length < 35 ? item.description : item.description.substr(0, 35) + "..." }</td>
                                <td class="text-center">${ item.num_of_employees }</td>
                                <td class="text-center">${ new Date(item.training_date).toLocaleString('sr-SR', { timeZone: 'CET' }) }, ${ item.place }</td>
                                <td class="text-center">${ item.resources }</td>
                                <td class="text-center">${ item.final_num_of_employees != null ? item.final_num_of_employees : "/" }</td>
                                <td class="text-center">${ item.rating != null ? item.rating : "/" }</td>
                                <td class="text-center">
                                    <span class="${ item.isAdmin === false ? 'd-none' : '' }">
                                        <a href="/trainings/${ item.id }/edit"><i class="fas fa-edit"></i></a>
                                        <a style="cursor: pointer; color: red;" id="delete-training" onclick="deleteTraining(${ item.id })" data-id="${ item.id }"><i class="fas fa-trash"></i></a>
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

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });

</script>
