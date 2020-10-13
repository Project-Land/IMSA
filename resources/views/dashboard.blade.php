<x-app-layout>
    <x-slot name="header">
        <h2>Standardi</h2>
    </x-slot>

    @foreach($standards as $standard)
        <a href="{{ route('standard', $standard->id) }}">{{ $standard->name }}</a>
    @endforeach
</x-app-layout>