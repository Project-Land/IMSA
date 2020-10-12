<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Standard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <table class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Version</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($documents as $document)
            <tr>
                <td>{{ $document->doc_name }}</td>
                <td>{{ $document->version }}</td>
                <td>
                    <a href="">Donwload</a>
                    <a href="">Edit</a>
                    <a href="">Delete</a>
                </td>
            </tr>   
        @endforeach
        </tbody>
    </table>

                
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    $('.yajra-datatable').DataTable(); 
</script>
