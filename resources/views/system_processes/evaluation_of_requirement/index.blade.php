<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Vrednovanje zakonskih i drugih zahteva') }}
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
                            @can('create', App\Models\EvaluationOfLegalAndOtherRequirement::class)
                                <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('evaluation-of-requirements.create') }}"><i class="fas fa-plus"></i> Kreiraj vrednovanje zahteva</a>
                            @endcan
                        </div>
                    
                    </div>
                </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>Nivo sa kojeg zahtev potiče</th>
                                    <th>Naziv dokumenta/zakona, ili opis zahteva</th>
                                    <th>Ocena usaglašenosti</th>
                                    <th>Datum poslednjeg ažuriranja</th>
                                    <th>Napomena</th>
                                    <th class="no-sort">Akcije</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                            @foreach($EvaluationOfLegalAndOtherRequirement as $requirement )
                                <tr>
                                    <td class="text-center">{{ $requirement->requirement_level }}</td>
                                    <td class="text-center">{{ $requirement->document_name }}</td>
                                    <td class="text-center">{{ $requirement->compliance ? 'Usaglašen':'Neusaglašen' }}</td>
                                    <td class="text-center">{{ date('d.m.Y', strtotime($requirement->updated_at)) }}</td>
                                    <td class="text-center">{{ $requirement->note ?? '/' }}</td>
                                    
                                    <td class="text-center">
                                       
                                        @canany(['update', 'delete'], $requirement)
                                        <a data-toggle="tooltip" data-placement="top" title="Izmena vrednovanja" href="{{ route('evaluation-of-requirements.edit', $requirement->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" id="delete-form-{{ $requirement->id }}" action="{{ route('evaluation-of-requirements.destroy', $requirement->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="text-red-600 cursor-pointer hover:text-red-800" type="button" data-toggle="tooltip" data-placement="top" title="Brisanje vrednovanja" onclick="confirmDeleteModal({{ $requirement->id }})"><i class="fas fa-trash"></i></button>
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
                    <div class="text-lg">Brisanje vrednovanja</div>
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
        "order": [[ 6, "desc" ]]
    });

   

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

   

    function confirmDeleteModal($id){
        let id = $id;
        $('#confirm-delete-modal').modal();
        $('#confirm-delete-modal').on('click', '.btn-ok', function(e) {
            let form = $('#delete-form-'+id);
            form.submit();
        });
    }

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>
