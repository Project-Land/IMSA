<x-app-layout>
    @push('scripts')
        <!-- Datatable -->
        <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    @endpush

    <x-slot name="header">
    <div class="flex flex-row justify-between">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Zainteresovane strane') }}
        </h2>
        @include('includes.video')
    </div>
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
                <div class="card-header">
                    @can('create', App\Models\Stakeholder::class)
                        <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('stakeholders.create') }}"><i class="fas fa-plus"></i> {{ __('Kreiraj zainteresovanu stranu')}}</a>
                    @endcan
                    <a class="inline-block float-right text-xs md:text-base bg-green-500 hover:bg-green-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('stakeholders.export') }}"><i class="fas fa-file-export"></i> {{ __('Excel') }}</a>
                </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>{{ __('Zainteresovana strana')}}</th>
                                    <th>{{ __('Potrebe i očekivanja zainteresovane strane')}}</th>
                                    <th>{{ __('Odgovor preduzeća na potrebe i očekivanja')}}</th>
                                    <th>{{ __('Kreirao')}}</th>
                                    <th class="no-sort w-20">{{ __('Akcije')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($stakeholders as $s)
                                <tr>
                                    <td class="text-center">{{ $s->name }}</td>
                                    <td class="text-center">{{ Str::length($s->expectation) < 100 ? $s->expectation : Str::limit($s->expectation, 100) }}</td>
                                    <td class="text-center">{{ Str::length($s->response) < 100 ? $s->response : Str::limit($s->response, 100) }}</td>
                                    <td class="text-center">{{ $s->user->name }}</td>
                                    <td class="text-center">
                                    <button data-toggle="tooltip" data-placement="top" title="{{ __('Prikaz zainteresovane strane') }}" class="text-blue-700 hover:text-blue-900" onclick="showStakeholder({{ $s->id }})"><i class="fas fa-eye"></i></button>
                                    <a
                                        href="{{route('stakeholders.print',$s->id)}}" target="_blank" data-toggle="tooltip" data-placement="top" class="text-green-400 hover:text-green-600" title="{{__('Odštampaj')}}" ><i class="fas fa-print"></i>
                                    </a>
                                        @canany(['update', 'delete'], $s)
                                            <a data-toggle="tooltip" data-placement="top" title="{{ __('Izmena zainteresovane strane')}}" href="{{ route('stakeholders.edit', $s->id) }}"><i class="fas fa-edit"></i></a>
                                            <form class="inline" id="delete-form-{{ $s->id }}" action="{{ route('stakeholders.destroy', $s->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button class="text-red-600 cursor-pointer hover:text-red-800" type="button" data-toggle="tooltip" data-placement="top" title="{{ __('Brisanje zainteresovane strane')}}" onclick="confirmDeleteModal({{ $s->id }})"><i class="fas fa-trash"></i></button>
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
                    <div class="text-lg">{{ __('Brisanje zainteresovane strane')}}</div>
                    <div class="mt-4">{{ __('Da li ste sigurni?')}}</div>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Odustani')}}</button>
                    <a class="btn-ok hover:no-underline cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150 ml-2">{{ __('Obriši')}}</a>
                </div>
            </div>
        </div>
    </div>

    @push('page-scripts')
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

            $('[data-toggle="tooltip"]').tooltip();

            function showStakeholder(id){
                axios.get('/stakeholders/'+id)
                    .then((response) => {
                        let modal = `<div class="modal fade" id="showStakeholder-${ id }" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" style="max-width: 600px !important;" role="document">
                                            <div class="modal-content rounded-lg shadow-xl">
                                                <div class="modal-body">
                                                    <div class="row text-sm mt-6">
                                                        <div class="col-sm-5 mt-1 border-bottom font-weight-bold"><p>{{ __('Zainteresovana strana') }}</p></div>
                                                        <div class="col-sm-7 mt-1 border-bottom"><p>${ response.data.name }</p></div>
                                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Potrebe i očekivanja zainteresovane strane') }}</p></div>
                                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.expectation }</p></div>
                                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Odgovor preduzeća na potrebe i očekivanja') }}</p></div>
                                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.response }</p></div>
                                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }}</p></div>
                                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.user.name }</p></div>
                                                    </div>
                                                </div>
                                                <div class="px-6 py-4 bg-gray-100 text-right">
                                                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Zatvori') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                        $("body").append(modal);
                        $('#showStakeholder-'+id).modal();
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
        </script>
    @endpush

</x-app-layout>
