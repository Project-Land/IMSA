<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Zainteresovane strane') }}
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
                @can('create', App\Models\Stakeholder::class)
                <div class="card-header">
                    <a class="inline-block bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('stakeholders.create') }}"><i class="fas fa-plus"></i> Kreiraj zainteresovanu stranu</a>
                </div>
                @endcan
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>Zainteresovana strana</th>
                                    <th>Potrebe i očekivanja zainteresovane strane</th>
                                    <th>Odgovor preduzeća na potrebe i očekivanja</th>
                                    <th class="no-sort">Akcije</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stakeholders as $s)
                                <tr>
                                    <td class="text-center">{{ $s->name }}</td>
                                    <td class="text-center">{{ Str::length($s->expectation) < 100 ? $s->expectation : Str::limit($s->expectation, 100) }}</td>
                                    <td class="text-center">{{ Str::length($s->response) < 100 ? $s->response : Str::limit($s->response, 100) }}</td>
                                    @canany(['update', 'delete'], $s)
                                    <td class="text-center">
                                        <a data-toggle="tooltip" data-placement="top" title="Izmena zainteresovane strane" href="{{ route('stakeholders.edit', $s->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" action="{{ route('stakeholders.destroy', $s->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button data-toggle="tooltip" data-placement="top" title="Brisanje zainteresovane strane" class="button text-danger" type="submit" style="cursor: pointer;" onclick="return confirm('Da li ste sigurni?');"><i class="fas fa-trash"></i></button>
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
</script>
