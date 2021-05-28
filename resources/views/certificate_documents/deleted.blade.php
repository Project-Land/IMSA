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
            {{ __('Sertifikati') }} - {{__('Obrisani dokumenti')}}
        </h2>
        @include('includes.video')
    </div>
    </x-slot>

    <div class="row mt-1">
        <div class="col">
            @if(Session::has('status'))
                <x-alert :type="Session::get('status')[0]" :message="__(Session::get('status')[1])"/>
            @endif
        </div>
    </div>

    <div class="row">
    	<div class="col">
        	<a class="inline-flex items-center px-4 py-2 hover:no-underline bg-white border border-gray-300 rounded-sm font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" href="{{ route('certification-documents.index') }}"><i class="fas fa-arrow-left mr-1"></i> {{__('Nazad')}}</a>
     	</div>
    </div>

    <div class="row mt-3">

        <div class="col">

            <div class="card">

                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>{{__('Naziv sertifikata')}}</th>
                                    <th>{{__('Datum sticanja sertifikata')}}</th>
                                    <th>{{__('Datum isteka sertifikata')}}</th>
                                    <th class="no-sort">{{__('Akcije')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $document)
                                    <tr>
                                        <td class="text-center">{{ $document->name }}</td>
                                        <td class="text-center">{{ date('d.m.Y', strtotime($document->start_date)) }}</td>
                                        <td class="text-center">{{ date('d.m.Y', strtotime($document->end_date)) }}</td>
                                        <td class="text-center">
                                            <form class="inline" action="{{ route('document.preview') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="folder" value="{{ $folder }}">
                                                <input type="hidden" name="file_name" value="{{ $document->file_name }}">
                                                <button data-toggle="tooltip" data-placement="top" title="{{__('Pregled dokumenta')}}" class="text-blue-700 hover:text-blue-900" type="submit"><i class="fas fa-eye"></i></button>
                                            </form>
                                            <form class="inline" action="{{ route('document.download') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="folder" value="{{ $folder }}">
                                                <input type="hidden" name="file_name" value="{{ $document->file_name }}">
                                                <button data-toggle="tooltip" data-placement="top" title="{{__('Preuzimanje dokumenta')}}" class="text-gray-700 hover:text-gray-900" type="submit"><i class="fas fa-download"></i></button>
                                            </form>
                                            @canany(['update', 'delete'], $document)
                                            <form class="inline" id="restore-form-{{ $document->id }}" action="{{ route('certification-documents.restore', $document->id) }}" method="POST">
                                                @csrf
                                                <button data-toggle="tooltip" data-placement="top" title="{{__('Povraćaj dokumenta')}}" class="text-green-500 hover:text-green-700" type="button" id="restore" onclick="confirmRestoreModal({{ $document->id }})"><i class="fas fa-trash-restore"></i></button>
                                            </form>
                                            <form class="inline" id="delete-form-{{ $document->id }}" action="{{ route('certification-documents.force-destroy', $document->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button data-toggle="tooltip" data-placement="top" title="{{__('Trajno brisanje dokumenta')}}" class="text-red-600 cursor-pointer hover:text-red-800" type="button" id="delete" onclick="confirmDeleteModal({{ $document->id }})"><i class="fas fa-trash"></i></button>
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
                    <div class="text-lg">{{__('Trajno brisanje dokumenta')}}</div>
                    <div class="mt-4">{{__('Da li ste sigurni? Nakon brisanja, dokument će biti trajno uklonjen.')}}</div>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{__('Odustani')}}</button>
                    <a class="btn-ok hover:no-underline cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150 ml-2">{{__('Obriši')}}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm-restore-modal" tabindex="-1" role="dialog" aria-labelledby="restore-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="px-6 py-4">
                    <div class="text-lg">{{__('Vraćanje dokumenta')}}</div>
                    <div class="mt-4">{{__('Da li ste sigurni? Nakon vraćanja, dokument će ponovo biti vidljiv svima.')}}</div>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{__('Odustani')}}</button>
                    <a class="btn-ok hover:no-underline cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray active:bg-gray-600 transition ease-in-out duration-150 ml-2">{{__('Vrati')}}</a>
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

            function confirmDeleteModal($id){
                let id = $id;
                $('#confirm-delete-modal').modal();
                $('#confirm-delete-modal').on('click', '.btn-ok', function(e) {
                    let form = $('#delete-form-'+id);
                    form.submit();
                });
            }

            function confirmRestoreModal($id){
                let id = $id;
                $('#confirm-restore-modal').modal();
                $('#confirm-restore-modal').on('click', '.btn-ok', function(e) {
                    let form = $('#restore-form-'+id);
                    form.submit();
                });
            }

            $('[data-toggle="tooltip"]').tooltip();

        </script>
    @endpush

</x-app-layout>
