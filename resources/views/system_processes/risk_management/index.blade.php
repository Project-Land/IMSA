<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Upravljanje rizicima') }}
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
                    <a class="btn btn-info" href="{{ route('risk-management.create') }}"><i class="fas fa-plus"></i> Kreiraj rizik / priliku</a>
                </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>Opis rizika/prilike</th>
                                    <th>Verovatnoća</th>
                                    <th>Učestalost</th>
                                    <th>Ukupno</th>
                                    <th>Prihvatljivo</th>
                                    <th>Mera</th>
                                    <th class="no-sort">Akcije</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riskManagements as $risk)
                                <tr>
                                    <td>{{ $risk->description }}</td>
                                    <td class="text-center">{{ $risk->probability }}</td>
                                    <td class="text-center">{{ $risk->frequency }}</td>
                                    <td class="text-center">{{ $risk->total }}</td>
                                    <td class="text-center">{{ $risk->acceptable }}</td>
                                    <td class="text-center"></td>
                                    <td class="text-center">
                                        <a href="{{ route('risk-management.edit', $risk->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" action="{{ route('risk-management.destroy', $risk->id) }}" method="POST">
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
