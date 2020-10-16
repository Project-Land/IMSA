<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Godišnji plan obuke') }}
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
                        <div class="col-sm-4"><a class="btn btn-info" href="{{ route('trainings.create') }}"><i class="fas fa-plus"></i> Kreiraj godišnji plan obuke</a></div>
                        <div class="col-sm-8">
                            <form class="form-inline" action="{{ route('trainings.filter-year') }}" method="POST">
                                @csrf
                                <label for="year" class="mr-3">Godina</label>
                                <select name="year" id="year" class="form-control w-25 mr-2">
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ date('Y') == $year ? "selected" : "" }} >{{ $year }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary">Primeni</button>
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
                            <tbody>
                                @foreach($trainingPlans as $tp)
                                <tr>
                                    <td class="text-center">{{ $tp->year }}</td>
                                    <td class="text-center">{{ $tp->name }}</td>
                                    <td class="text-center">{{ $tp->type }}</td>
                                    <td class="text-center">{{ $tp->description }}</td>
                                    <td class="text-center">{{ $tp->num_of_employees }}</td>
                                    <td class="text-center">{{ date('d.m.Y', strtotime($tp->training_date)) }} u {{ date('H:i', strtotime($tp->training_date)) }}, {{ $tp->place }}</td>
                                    <td class="text-center">{{ $tp->resources }}</td>
                                    <td class="text-center">{{ $tp->final_num_of_employees == null ? "/" : $tp->final_num_of_employees }}</td>
                                    <td class="text-center">{{ $tp->rating == null ? "/" : $tp->rating }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('trainings.edit', $tp->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" action="{{ route('trainings.destroy', $tp->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="button" type="submit" style="cursor: pointer;" onclick="return confirm('Da li ste sigurni?');"><i class="fas fa-trash"></i></button>
                                        </form>
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
    let table = $('.yajra-datatable').DataTable({
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
</script>
