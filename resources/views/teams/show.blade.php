<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Podešavanja firme') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

            @if(Gate::check('create', $team))
                @livewire('teams.update-team-name-form', ['team' => $team])
            @endif

            @if(Gate::check('update', $team))
                <x-jet-section-border />
                @livewire('teams.team-member-manager', ['team' => $team])
            @endif

            @if (Gate::check('delete', $team) && ! $team->personal_team)
                <x-jet-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('teams.delete-team-form', ['team' => $team])
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
