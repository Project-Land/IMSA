<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Standard') }}
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
                    <a class="btn btn-info" href="{{ route($route_name.'.create') }}">Kreiraj novi dokument</a>
                </div>
                <div class="card-body bg-white mt-3">
                    <table class="table table-bordered yajra-datatable">
                        <thead>
                            <tr class="text-center">
                                <th>Naziv dokumenta</th>
                                <th>Verzija</th>
                                <th>Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($documents as $document)
                            <tr>
                                <td>{{ $document->document_name }}</td>
                                <td>{{ $document->version }}</td>
                                <td class="text-center">
                                    <a href='{{ asset("storage/$folder/$document->file_name") }}'><i class="fas fa-download"></i></a>
                                    <a href="{{ route($route_name.'.edit', $document->id) }}"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route($route_name.'.destroy', $document->id) }}"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>   
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>  

        </div>

    </div>

</x-app-layout>

<script>
    $('.yajra-datatable').DataTable(); 
</script>
