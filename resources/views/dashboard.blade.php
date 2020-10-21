<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="row mt-5">
        <div class="col">
            <div class="card-columns">
                @foreach($standards as $standard)
                <div class="card bg-light shadow-sm rounded-0">
                    <div class="card-body text-center">
                        <p class="card-text display-4">
                            <a class="text-decoration-none" href="{{ route('standard', $standard->id) }}">{{ $standard->name }}</a>
                        </p>
                    </div>
                </div>   
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
