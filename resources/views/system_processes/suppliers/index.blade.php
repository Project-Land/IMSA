<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Odobreni isporučioci') }}
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
                @can('create', App\Models\Supplier::class)
                <div class="card-header">
                    <a class="btn btn-info" href="{{ route('suppliers.create') }}"><i class="fas fa-plus"></i> Kreiraj isporučioca</a>
                </div>
                @endcan
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>Naziv isporučioca</th>
                                    <th>Predmet nabavke</th>
                                    <th>Status</th>
                                    <th>Datum ažuriranja</th>
                                    <th>Datum sledećeg preispitivanja</th>
                                    <th class="no-sort">Akcije</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliers as $s)
                                <tr>
                                    <td class="text-center">{{ $s->supplier_name }}</td>
                                    <td class="text-center">{{ $s->subject }}</td>
                                    <td class="text-center">{{ ($s->status == '1') ? 'Odobren' : 'Neodobren' }}</td>
                                    <td class="text-center">{{ date('d.m.Y', strtotime($s->created_at)) }}</td>
                                    <td class="text-center">{{ date('d.m.Y', strtotime($s->deadline_date)) }}</td>
                                    @canany(['update', 'delete'], $s)
                                    <td class="text-center">
                                        <a href="{{ route('suppliers.edit', $s->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" action="{{ route('suppliers.destroy', $s->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="button text-danger" type="submit" style="cursor: pointer;" onclick="return confirm('Da li ste sigurni?');"><i class="fas fa-trash"></i></button>
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
</script>
