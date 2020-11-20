<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Session::get('standard_name') }} - {{ __('Reklamacije') }}
        </h2>
    </x-slot>

    <div class="row mt-1">
        <div class="col">
            @if(Session::has('status'))
                <x-alert :type="Session::get('status')[0]" :message="Session::get('status')[1]"/>
            @endif
        </div>
    </div>

    <div class="row mt-3">

        <div class="col">

            <div class="card">
                @can('create', App\Models\Complaint::class)
                <div class="card-header">
                    <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('complaints.create') }}"><i class="fas fa-plus"></i> Kreiraj reklamaciju</a>
                </div>
                @endcan
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
                                    <td class="text-center">{{ $c->name }}</td>
                                    <td class="text-center">{{ date('d.m.Y', strtotime($c->submission_date)) }}</td>
                                    <td class="text-center">{{ Str::length($c->description) < 35 ? $c->description : Str::limit($c->description, 35) }}</td>
                                    <td class="text-center">{{ $c->process }}</td>
                                    <td class="text-center">{{ $c->accepted == 1 ? "DA" : "NE" }}</td>
                                    <td class="text-center">{{ $c->deadline_date != null ? date('d.m.Y', strtotime($c->deadline_date)) : "/" }}</td>
                                    <td class="text-center">{{ $c->responsible_person ? : "/" }}</td>
                                    <td class="text-center">{{ $c->way_of_solving ? : "/" }}</td>
                                    <td class="text-center">{{ ($c->status == '1') ? 'Otvorena' : 'Zatvorena' }}</td>
                                    <td class="text-center">
                                        @canany(['update', 'delete'], $c)
                                        <a data-toggle="tooltip" data-placement="top" title="Izmena reklamacije" href="{{ route('complaints.edit', $c->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" id="delete-form-{{ $c->id }}" action="{{ route('complaints.destroy', $c->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="button" class="text-red-600 cursor-pointer hover:text-red-800" data-toggle="tooltip" data-placement="top" title="Brisanje reklamacije" onclick="confirmDeleteModal({{ $c->id }})"><i class="fas fa-trash"></i></button>
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
                    <div class="text-lg">Brisanje reklamacije</div>
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
        "order": [[ 1, "desc" ]]
    });

    function confirmDeleteModal($id){
        let id = $id;
        $('#confirm-delete-modal').modal();
        $('#confirm-delete-modal').on('click', '.btn-ok', function(e) {
            let form = $('#delete-form-'+id);
            form.submit();
        });
    }

    $('[data-toggle="tooltip"]').tooltip();

</script>
