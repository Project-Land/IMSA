<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Standard') }} {{session('standard_name')}}
           
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
                    <a class="btn btn-info" href="{{ route('goals.create') }}"><i class="fas fa-plus"></i> Kreiraj novi dokument</a>
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
                            <tbody>
                            @foreach($goals as $goal)
                                <tr>
                                    <td>{{ $goal->year }}</td>
                                    <td>{{ $goal->goal }}</td>
                                    <td>{{ $goal->kpi }}</td>
                                    <td>{{ $goal->activities }}</td>
                                    <td>{{ $goal->responsibility }}</td>
                                    <td>{{ $goal->deadline }}</td>
                                    <td>{{ $goal->resources }}</td>
                                    <td class="text-center">{{ $goal->analysis }}</td>
                                    <td class="text-center">
                                    <button class="button" id="open_modal_button"><i class="fas fa-eye"></i></button>
                                        <a href="{{ route('goals.edit', $goal->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" action="{{ route('goals.destroy', $goal->id) }}" method="POST">
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
</script>