<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{session('standard_name')}} - {{ __('Neusaglašenosti i korektivne mere') }} 
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
                    <a class="btn btn-info" href="{{ route('corrective-measures.create') }}"><i class="fas fa-plus"></i> Kreiraj novu neusaglašenost / meru</a>
                </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>Br. kartona</th>
                                    <th>Datum pokretanja</th>
                                    <th>Sistem menadžment</th>
                                    <th>Status</th>
                                    <th class="no-sort">Akcije</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($measures as $measure)
                                <tr>
                                    <td class="text-center">{{ $measure->name }}</td>
                                    <td class="text-center">{{ date('d.m.Y', strtotime($measure->created_at)) }}</td>
                                    <td class="text-center">{{ $measure->standard->name }}</td>
                                    <td class="text-center">{{ $measure->measure_status == '0'? "Otvorena" : "Zatvorena" }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('corrective-measures.show', $measure->id) }}"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('corrective-measures.edit', $measure->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" action="{{ route('corrective-measures.destroy', $measure->id) }}" method="POST">
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
