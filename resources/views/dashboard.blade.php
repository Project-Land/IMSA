<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Početna') }}
        </h2>
    </x-slot>

    <div class="row">
        <div class="col mt-4">

            <div class="row mt-1">
                <div class="col mx-2">
                    @if(Session::has('status'))
                        <x-alert :type="Session::get('status')[0]" :message="Session::get('status')[1]"/>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-5 md:p-20 mx-2">
                <div class="text-center">
                    <img class="inline-block items-center" src="{{ 'storage/logos/'.\Auth::user()->currentTeam->logo }}" alt="{{ \Auth::user()->currentTeam->name }} Logo">
                    <h3 class='text-xl md:text-3xl mt-1'>{{ __('Sistem Menadžment') }}</h3>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
