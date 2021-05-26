<x-app-layout>
    @push('scripts')
        <!-- Datatable -->
        <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    @endpush

    <x-slot name="header">
    <div class="row">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight col-10">
            {{ Session::get('standard_name') }} - {{ __($doc_type) }}
        </h2>
        <div><i class="fas fa-video cursor-pointer text-2xl col-2 text-green-400" id="on" data-toggle="tooltip" data-placement="top" title="{{__('Video pomoć')}}" ></i></div>
        <iframe id="youtube" class="w-full h-1/3 md:w-1/2 md:h-2/5 lg:w-1/3" style="position:fixed;top:0px;right:0px;z-index:1000;display:none;"  src="{{'https://www.youtube-nocookie.com/embed/'.$help_video.'?rel=0'}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe><button id="off" style="position:fixed;top:0px;right:5px;background:red;z-index:1001;font-size:40px;font-weight:900;display:none;border:2px solid black;">&nbsp;X&nbsp;</button>
    </div>
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
                @can('create', App\Models\Document::class)
                    <div class="card-header">
                        <div class="flex flex-wrap justify-between">
                            <a class="inline-flex hover:no-underline items-center px-4 py-2 bg-blue-500 border border-transparent rounded font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150"  href="{{ route($route_name.'.create') }}"><i class="fas fa-plus mr-2"></i> {{ __('Kreiraj novi dokument') }}</a>
                            <a class="inline-flex hover:no-underline items-center px-4 py-2 bg-red-500 border border-transparent rounded font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150"  href="{{ route($route_name.'.deleted') }}"><i class="fas fa-trash mr-2"></i> {{ __('Obrisani dokumenti') }}</a>
                        </div>
                    </div>
                @endcan
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>{{__('Naziv dokumenta')}}</th>
                                    @unless($route_name == 'external-documents' || $route_name == 'other-internal-documents')<th>{{__('Verzija')}}</th>@endunless
                                    @if($route_name == 'procedures' || $route_name == 'forms' || $route_name == 'manuals')<th>{{__('Sektor')}}</th>@endif
                                    <th class="no-sort">{{__('Akcije')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $document)
                                    <tr>
                                        <td class="text-center">{{ $document->document_name }}</td>
                                        @unless($route_name == 'external-documents' || $route_name == 'other-internal-documents')<td class="text-center">{{ $document->version }}</td>@endunless
                                        @if($route_name == 'procedures' || $route_name == 'forms' || $route_name == 'manuals')<td class="text-center">{{ $document->sector->name }}</th>@endif
                                        <td class="text-center">
                                            @if($route_name != 'forms')
                                                <form class="inline" action="{{ route('document.preview') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="folder" value="{{ $folder }}">
                                                    <input type="hidden" name="file_name" value="{{ $document->file_name }}">
                                                    <button data-toggle="tooltip" data-placement="top" title="{{__('Pregled dokumenta')}}" class="text-blue-700 hover:text-blue-900" type="submit"><i class="fas fa-eye"></i></button>
                                                </form>
                                            @endif
                                            <form class="inline" action="{{ route('document.download') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="folder" value="{{ $folder }}">
                                                <input type="hidden" name="file_name" value="{{ $document->file_name }}">
                                                <button data-toggle="tooltip" data-placement="top" title="{{__('Preuzimanje dokumenta')}}" class="text-gray-700 hover:text-gray-900 button" type="submit"><i class="fas fa-download"></i></button>
                                            </form>
                                            @canany(['update', 'delete'], $document)
                                                <a data-toggle="tooltip" data-placement="top" title="{{__('Izmena dokumenta')}}" href="{{ route($route_name.'.edit', $document->id) }}"><i class="fas fa-edit"></i></a>
                                                <form class="inline" id="delete-form-{{ $document->id }}" action="{{ route($route_name.'.destroy', $document->id) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button data-toggle="tooltip" data-placement="top" title="{{__('Brisanje dokumenta')}}" class="text-red-600 cursor-pointer hover:text-red-800" type="button" id="delete" onclick="confirmDeleteModal({{ $document->id }})"><i class="fas fa-trash"></i></button>
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
                    <div class="text-lg">{{__('Brisanje dokumenta')}}</div>
                    <div class="mt-4">{{__('Da li ste sigurni?')}}</div>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{__('Odustani')}}</button>
                    <a class="btn-ok hover:no-underline cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150 ml-2">{{__('Obriši')}}</a>
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

            $('[data-toggle="tooltip"]').tooltip();


            document.getElementById('off').addEventListener('click',()=>{
                let src=document.getElementById('youtube').src;
                document.getElementById('youtube').src=src;
                document.getElementById('youtube').style.display='none';
                document.getElementById('off').style.display='none';
            });

            document.getElementById('on').addEventListener('click',()=>{
                document.getElementById('youtube').style.display='block';
                document.getElementById('off').style.display='block';
            });

           
        </script>
    @endpush

</x-app-layout>
