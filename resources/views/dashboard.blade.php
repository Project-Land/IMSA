<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ __('Početna') }}
        </h2>
    </x-slot>

    <div class="row">
        <div class="col mt-4">

            <div class="row mt-1">
                <div class="col mx-2">
                    @if(Session::has('status'))
                        <x-alert :type="Session::get('status')[0]" :message="__(Session::get('status')[1])"/>
                    @endif
                </div>
            </div>

            @if(!Session::get('standard'))
                <div class="row">
                    <div class="col mx-2">
                        <div class="bg-green-200 border border-gray-400 text-gray-700 px-4 py-3 rounded relative text-center" role="alert">
                        <div class="hidden md:inline"><i class="fas fa-hand-pointer float-left text-red-500 text-2xl ml-32  md:visible"></i></div><strong class="text-xl md:mr-32">  {{ __('Izaberite standard!') }}</strong>
                          </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-lg p-5 md:p-20 mx-2">
                <div class="text-center">
                    <img class="inline-block items-center" src="{{ 'storage/logos/'.\Auth::user()->currentTeam->logo }}" alt="{{ \Auth::user()->currentTeam->name }} Logo">
                    <h3 class='text-xl md:text-3xl mt-1'>{{ __('Sistem Menadžment') }}</h3>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
