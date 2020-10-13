<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Standard') }} {{ $standard->name }}
        </h2>
    </x-slot>

    <div class="row mt-3">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <li><a href="/rules-of-procedures">Poslovnik</a></li>
                    <li><a href="/policies">Politike</a></li>
                    <li><a href="/procedures">Procedure</a></li>
                    <li><a href="/manuals">Uputstva</a></li>
                    <li><a href="/forms">Forme</a></li>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
