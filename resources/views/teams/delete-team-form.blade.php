<x-jet-action-section>
    <x-slot name="title">
        {{ __('Ukloni Firmu') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Trajno ukloni ovu firmu') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('Nakon brisanja firme, svi podaci vezani za firmu će biti uklonjeni. Pre brisanja, molimo vas da preuzmete sve podatke koji vam mogu biti neophodni.') }}
        </div>

        <div class="mt-5">
            <x-jet-danger-button wire:click="$toggle('confirmingTeamDeletion')" wire:loading.attr="disabled">
                {{ __('Ukloni Firmu') }}
            </x-jet-danger-button>
        </div>

        <!-- Delete Team Confirmation Modal -->
        <x-jet-confirmation-modal wire:model="confirmingTeamDeletion">
            <x-slot name="title">
                {{ __('Ukloni Firmu') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Da li ste sigurni da želite da uklonite ovu firmu? Svi podaci vezani za firmu će nakon toga biti trajno uklonjeni.') }}
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingTeamDeletion')" wire:loading.attr="disabled">
                    {{ __('Odustani') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="deleteTeam" wire:loading.attr="disabled">
                    {{ __('Ukloni Firmu') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-confirmation-modal>
    </x-slot>
</x-jet-action-section>
