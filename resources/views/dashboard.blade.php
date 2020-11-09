<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Početna') }}
        </h2>
    </x-slot>

    <div class="flex items-center justify-center">
        <div class="container mt-4">

            <div class="row mt-1">
                <div class="col mx-2">
                    @if(Session::has('status'))
                        <div class="alert alert-secondary alert-dismissible fade show">
                            {{ Session::get('status') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-5 md:p-20 mx-2">
                <div class="text-center">
                    <img class="inline-block items-center" src="{{ 'storage/logos/'.\Auth::user()->currentTeam->logo }}" alt="{{ \Auth::user()->currentTeam->name }} Logo">
                    <h3 class='text-xl md:text-3xl mt-1'>Sistem Menadžment</h3>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
