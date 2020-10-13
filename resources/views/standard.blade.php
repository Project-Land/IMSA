<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Standard') }} {{ $standard->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <li><a href="/rules-of-procedures">Poslovnik</a></li>
                <li><a href="">Politike</a></li>
                <li><a href="">Procedure</a></li>
                <li><a href="">Uputstva</a></li>
                <li><a href="">Obrasci</a></li>
            </div>
        </div>
    </div>

</x-app-layout>
