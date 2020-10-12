
<x-app-layout>
    <x-slot name="header">
        <h2>Standardi</h2>
    </x-slot>
    
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          @foreach($standards as $standard)
          <a href="{{ route('standard', $standard->id) }}">{{ $standard->name }}</a>
          @endforeach
        </div>
      </div>
    </div>
    
</x-app-layout>
