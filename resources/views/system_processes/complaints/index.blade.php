<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Reklamacije') }}
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
                    <a class="btn btn-info" href="{{ route('complaints.create') }}"><i class="fas fa-plus"></i> Kreiraj reklamaciju</a>
                </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>Oznaka</th>
                                    <th>Datum podnošenja</th>
                                    <th>Opis</th>
                                    <th>Proces na koji se reklamacija odnosi</th>
                                    <th>Opravdana / prihvaćena</th>
                                    <th>Rok za realizaciju</th>
                                    <th>Lice odgovorno za rešavanje</th>
                                    <th>Način rešavanja</th>
                                    <th>Status</th>
                                    <th class="no-sort">Akcije</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($complaints as $c)
                                <tr>
                                    <td>{{ $c->name }}</td>
                                    <td class="text-center">{{ date('d.m.Y', strtotime($c->submission_date)) }}</td>
                                    <td>{{ $c->description }}</td>
                                    <td>{{ $c->process }}</td>
                                    <td>{{ $c->accepted == 1 ? "DA" : "NE" }}</td>
                                    <td class="text-center">{{ $c->deadline_date != null ? date('d.m.Y', strtotime($c->deadline_date)) : "/" }}</td>
                                    <td>{{ $c->responsible_person != null ? $c->responsible_person : "/" }}</td>
                                    <td>{{ $c->way_of_solving != null ? $c->way_of_solving : "/" }}</td>
                                    <td class="text-center">{{ ($c->status == '1') ? 'Otvorena' : 'Zatvorena' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('complaints.edit', $c->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" action="{{ route('complaints.destroy', $c->id) }}" method="POST">
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
