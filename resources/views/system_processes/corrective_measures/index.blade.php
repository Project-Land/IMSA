<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{session('standard_name')}} - {{ __('Neusaglašenosti i korektivne mere') }}
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
                @can('create', App\Models\CorrectiveMeasure::class)
                <div class="card-header">
                    <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('corrective-measures.create') }}"><i class="fas fa-plus"></i> {{ __('Kreiraj novu neusaglašenost / meru') }}</a>
                </div>
                @endcan
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>{{ __('Br. kartona') }}</th>
                                    <th>{{ __('Datum pokretanja') }}</th>
                                    <th>{{ __('Sistem menadžment') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="no-sort">{{ __('Akcije') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($measures as $measure)
                                <tr>
                                    <td class="text-center">{{ $measure->name }}</td>
                                    <td class="text-center">{{ date('d.m.Y', strtotime($measure->created_at)) }}</td>
                                    <td class="text-center">{{ $measure->standard->name }}</td>
                                    <td class="text-center">{{ $measure->measure_effective === 0 || $measure->measure_effective === null ? __('Otvorena') : __('Zatvorena') }}</td>
                                    <td class="text-center">
                                        <button data-toggle="tooltip" data-placement="top" title="{{ __('Prikaz korektivne mere') }}" class="text-primary" onclick="showMeasure({{ $measure->id }})"><i class="fas fa-eye"></i></button>
                                        @canany(['update', 'delete'], $measure)
                                        <a data-toggle="tooltip" data-placement="top" title="{{ __('Izmena korektivne mere') }}" href="{{ route('corrective-measures.edit', $measure->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" id="delete-form-{{ $measure->id }}" action="{{ route('corrective-measures.destroy', $measure->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button  class="text-red-600 cursor-pointer hover:text-red-800" type="button" data-toggle="tooltip" data-placement="top" title="{{ __('Brisanje korektivne mere') }}" onclick="confirmDeleteModal({{ $measure->id }})"><i class="fas fa-trash"></i></button>
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
                    <div class="text-lg">{{ __('Brisanje neusaglašenosti / korektivne mere') }}</div>
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
        "order": [[ 2, "desc" ]]
    });

    function showMeasure(id){
        axios.get('/corrective-measures/'+id)
            .then((response) => {
                let modal = `<div class="modal fade" id="showData-${ id }" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header text-center">
                                            <h5 class="modal-title font-weight-bold">${ response.data.name }</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row text-sm">
                                                <div class="col-sm-5 mt-1 border-bottom font-weight-bold"><p>{{ __('Datum kreiranja') }}</p></div>
                                                <div class="col-sm-7 mt-1 border-bottom"><p>${ new Date(response.data.created_at).toLocaleString('sr-SR', { timeZone: 'CET' }) }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Sistem menadžment') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.standard.name }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Izvor neusaglašenosti') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>{{ __('${ response.data.noncompliance_source }') }}</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Organizaciona celina') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.sector.name }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Opis neusaglašenosti') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.noncompliance_description }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Uzrok neusaglašenosti') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.noncompliance_cause }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Mera za otklanjanje neusaglašenosti') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measure }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Mera odobrena') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measure_approval == 1 ? "{{ __('Odobrena') }}" : "{{ __('Neodobrena') }}" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Razlog neodobravanja mere') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measure_approval_reason == null ? "/" : response.data.measure_approval_reason }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Datum odobravanja mere') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measure_approval_date != null ? new Date(response.data.measure_approval_date).toLocaleDateString('sr-SR', { timeZone: 'CET' }) : "/" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Mera efektivna') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measure_effective != null ? response.data.measure_effective == 1 ? "{{ __('Efektivna') }}" : "{{ __('Neefektivna') }}" : "/" }</p></div>
                                            </div>
                                        </div>
                                        <div class="px-6 py-4 bg-gray-100 text-right">
                                            <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Zatvori') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                $("body").append(modal);
                $('#showData-'+id).modal();
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
