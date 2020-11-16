<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Session::get('standard_name') }} - {{ $doc_type }} - Izbrisani dokumenti
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

    <div class="row">
    	<div class="col">
        	<a class="inline-flex items-center px-4 py-2 hover:no-underline bg-white border border-gray-300 rounded-sm font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" href="{{ $back }}"><i class="fas fa-arrow-left mr-1"></i> Nazad</a>
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
                                    <th>Naziv dokumenta</th>
                                    <th>Verzija</th>
                                    @if($route_name == 'procedures' || $route_name == 'forms' || $route_name == 'manuals')<th>Sektor</th>@endif
                                    <th class="no-sort">Akcije</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $document)
                                    <tr>
                                        <td class="text-center">{{ $document->document_name }}</td>
                                        <td class="text-center">{{ $document->version }}</td>
                                        @if($route_name == 'procedures' || $route_name == 'forms' || $route_name == 'manuals')<td class="text-center">{{ $document->sector->name }}</th>@endif
                                        <td class="text-center">
                                            @if($route_name != 'forms')
                                            <form class="inline" action="{{ route('document.preview') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="folder" value="{{ $folder }}">
                                                <input type="hidden" name="file_name" value="{{ $document->file_name }}">
                                                <button data-toggle="tooltip" data-placement="top" title="Pregled dokumenta" class="button text-primary" type="submit" style="cursor: pointer;"><i class="fas fa-eye"></i></button>
                                            </form>
                                            @endif
                                            <form class="inline" action="{{ route('document.download') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="folder" value="{{ $folder }}">
                                                <input type="hidden" name="file_name" value="{{ $document->file_name }}">
                                                <button data-toggle="tooltip" data-placement="top" title="Preuzimanje dokumenta" class="button" type="submit" style="cursor: pointer;"><i class="fas fa-download"></i></button>
                                            </form>
                                            @canany(['update', 'delete'], $document)
                                            <form class="inline" action="{{ route($route_name.'.restore', $document->id) }}" method="POST">
                                                @csrf
                                                <button data-toggle="tooltip" data-placement="top" title="Povraćaj dokumenta" class="button text-success" type="submit" style="cursor: pointer;" onclick="return confirm('Da li ste sigurni?');" id="restore"><i class="fas fa-trash-restore"></i></button>
                                            </form>
                                            <form class="inline" action="{{ route($route_name.'.force-destroy', $document->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button data-toggle="tooltip" data-placement="top" title="Trajno brisanje dokumenta" class="button text-danger" type="submit" style="cursor: pointer;" onclick="return confirm('Da li ste sigurni?');" id="delete"><i class="fas fa-trash"></i></button>
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

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });

</script>
