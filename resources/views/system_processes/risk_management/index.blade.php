<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Rizici i prilike') }}
        </h2>
    </x-slot>

    <div class="row mt-1">
        <div class="col">
            @if(Session::has('status'))
                <x-alert :type="Session::get('status')[0]" :message="__(Session::get('status')[1])"/>
            @endif
        </div>
    </div>

    <div class="row mt-3">

        <div class="col">

            <div class="card">
                <div class="card-header">
                    @can('create', App\Models\RiskManagement::class)
                        <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('risk-management.create') }}"><i class="fas fa-plus"></i> {{ __('Kreiraj rizik / priliku') }}</a>
                    @endcan
                    {{-- <a class="inline-block float-right text-xs md:text-base bg-green-500 hover:bg-green-700 text-white hover:no-underline rounded py-2 px-3" href="#"><i class="fas fa-file-export"></i> {{ __('Excel') }}</a> --}}
                </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>{{ __('Opis rizika / prilike') }}</th>
                                    <th>{{ __('Verovatnoća') }}</th>
                                    <th>{{ __('Posledice') }}</th>
                                    <th>{{ __('Ukupno') }}</th>
                                    <th>{{ __('Prihvatljivo') }}</th>
                                    <th>{{ __('Mera') }}</th>
                                    <th>{{ __('Kreirao') }}</th>
                                    <th class="no-sort">{{ __('Akcije') }}</th>
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
                                    <td class="text-center text-sm"><span @if($risk->measure) style="cursor: pointer; color: blue;" onclick="showMeasure({{ $risk->id }})" @endif >{{ ($risk->measure) ? : '/' }}</span>
                                        @if($risk->measure)
                                            @can('update', $risk)
                                                <a href="{{ route('risk-management.edit-plan', $risk->id) }}"><i class="fas fa-pen"></i></a>
                                            @endcan
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $risk->user->name }}</td>
                                    <td class="text-center">
                                        @canany(['update', 'delete'], $risk)
                                        <a data-toggle="tooltip" data-placement="top" title="{{ __('Izmena rizika/prilike') }}" href="{{ route('risk-management.edit', $risk->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" id="delete-form-{{ $risk->id }}" action="{{ route('risk-management.destroy', $risk->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="text-red-600 cursor-pointer hover:text-red-800" type="button" data-toggle="tooltip" data-placement="top" title="{{ __('Brisanje rizika/prilike') }}" onclick="confirmDeleteModal({{ $risk->id }})"><i class="fas fa-trash"></i></button>
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
                    <div class="text-lg">{{ __('Brisanje rizika/prilike') }}</div>
                    <div class="mt-4">{{ __('Da li ste sigurni?') }}</div>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Odustani') }}</button>
                    <a class="btn-ok hover:no-underline cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150 ml-2">{{ __('Obriši') }}</a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<script>
    $('.yajra-datatable').DataTable({
        "language": {
            "info": "{{__('Prikaz strane')}} _PAGE_ {{__('od')}} _PAGES_",
            "infoEmpty": "{{__('Nema podataka')}}",
            "zeroRecords": "{{__('Nema podataka')}}",
            "infoFiltered": "({{__('od')}} _MAX_ {{__('ukupno rezultata')}})",
            "lengthMenu": "{{__('Prikaži')}} _MENU_ {{__('redova po stranici')}}",
            "search": "{{__('Pretraga')}}",
            "paginate": {
                "next": "{{__('Sledeća')}}",
                "previous": "{{__('Prethodna')}}",
                "first": "{{__('Prva')}}",
                "last": "{{__('Poslednja')}}"
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
                                                <div class="col-sm-5 mt-1 border-bottom font-weight-bold"><p>{{ __('Opis rizika / prilike') }}</p></div>
                                                <div class="col-sm-7 mt-1 border-bottom"><p>${ response.data.description }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Uzrok') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.cause != null ? response.data.cause : "/" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Mera za smanjenje rizika/ korišćenje prilike') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.risk_lowering_measure != null ? response.data.risk_lowering_measure : "/" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Odgovornost') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.responsibility != null ? response.data.responsibility : "/" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Rok za realizaciju') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.deadline != null ? new Date(response.data.deadline).toLocaleDateString('sr-SR', { timeZone: 'CET' }) : "/" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Status') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.status == 0 ? "{{ __('Zatvorena') }}" : "{{ __('Otvorena') }}" }</p></div>
                                            </div>
                                        </div>
                                        <div class="px-6 py-4 bg-gray-100 text-right">
                                            <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Zatvori') }}</button>
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

    $('[data-toggle="tooltip"]').tooltip();

</script>
