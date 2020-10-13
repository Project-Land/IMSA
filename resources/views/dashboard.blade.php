<x-app-layout>
    <x-slot name="header">
        
    </x-slot>

    <div class="row mt-5">
        <div class="col">
            <div class="card-columns">
                @foreach($standards as $standard)
                <div class="card bg-light">
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