<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Rizici i prilike') }}
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
                    @can('create', App\Models\RiskManagement::class)
                        <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('risk-management.create') }}"><i class="fas fa-plus"></i> Kreiraj rizik / priliku</a>
                    @endcan
                    </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>Opis rizika / prilike</th>
                                    <th>Verovatnoća</th>
                                    <th>Posledice</th>
                                    <th>Ukupno</th>
                                    <th>Prihvatljivo</th>
                                    <th>Mera</th>
                                    <th class="no-sort">Akcije</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riskManagements as $risk)
                                <tr>
                                    <td class="text-center">{{ Str::length($risk->description) < 60 ? $risk->description : Str::limit($risk->description, 60) }}</td>
                                    <td class="text-center">{{ $risk->probability }}</td>
                                    <td class="text-center">{{ $risk->frequency }}</td>
                                    <td class="text-center">{{ $risk->total }}</td>
                                    <td class="text-center">{{ $risk->acceptable }}</td>
                                    <td class="text-center"><span @if($risk->measure) style="cursor: pointer; color: blue;"  onclick="showMeasure({{ $risk->id }})" @endif >{{ ($risk->measure) ? : '/' }}</span>
                                        @if($risk->measure)
                                            @can('update',$risk)
                                            <a href="{{ route('risk-management.edit-plan', $risk->id) }}"><i class="fas fa-pen"></i></a>
                                            @endcan
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @canany(['update', 'delete'], $risk)
                                        <a data-toggle="tooltip" data-placement="top" title="Izmena rizika/prilike" href="{{ route('risk-management.edit', $risk->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" id="delete-form-{{ $risk->id }}" action="{{ route('risk-management.destroy', $risk->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="text-red-600 cursor-pointer hover:text-red-800" type="button" data-toggle="tooltip" data-placement="top" title="Brisanje rizika/prilike" onclick="confirmDeleteModal({{ $risk->id }})"><i class="fas fa-trash"></i></button>
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
                    <div class="text-lg">Brisanje rizika / prilike</div>
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
    });

    function showMeasure(id){
        axios.get('/risk-management/'+id)
            .then((response) => {
                let modal = `<div class="modal fade" id="showMeasure-${ id }" tabindex="-1" role="dialog">
                                <div class="modal-dialog" style="max-width: 600px !important;" role="document">
                                    <div class="modal-content rounded-lg shadow-xl">
                                        <div class="modal-header">
                                            <h5 class="modal-title font-weight-bold">${ response.data.measure }</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row text-sm">
                                                <div class="col-sm-5 mt-1 border-bottom font-weight-bold"><p>Opis rizika / prilike</p></div>
                                                <div class="col-sm-7 mt-1 border-bottom"><p>${ response.data.description }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Uzrok</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.cause != null ? response.data.cause : "/" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Mera za smanjenje rizika</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.risk_lowering_measure != null ? response.data.risk_lowering_measure : "/" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Odgovornost</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.responsibility != null ? response.data.responsibility : "/" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Rok za realizaciju</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.deadline != null ? new Date(response.data.deadline).toLocaleDateString('sr-SR', { timeZone: 'CET' }) : "/" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Status</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.status == 0 ? "Zatvorena" : "Otvorena" }</p></div>
                                            </div>
                                        </div>
                                        <div class="px-6 py-4 bg-gray-100 text-right">
                                            <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">Zatvori</button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                $("body").append(modal);
                $('#showMeasure-'+id).modal();
            })
            .catch((error) => {
                console.log(error)
            })
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
