<x-jet-action-section>
    <x-slot name="title">
        {{ __('Uklanjanje naloga') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Trajno uklonite vaš nalog') }}.
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('Nakon što uklonite nalog, svi podaci vezani za njega biće trajno uklonjeni. Pre brisanja naloga, molimo vas da preuzmete sve potrebne podatke vezane za vaš nalog') }}.
        </div>

        <div class="mt-5">
            <x-jet-danger-button wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                {{ __('Uklonite nalog') }}
            </x-jet-danger-button>
        </div>

        <!-- Delete User Confirmation Modal -->
        <x-jet-dialog-modal wire:model="confirmingUserDeletion">
            <x-slot name="title">
                {{ __('Uklanjanje naloga') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Da li ste sigurni da želite da uklonite vaš nalog? Nakon uklanjanja, svi podaci vezani za nalog će biti obrisani. Unesite vašu lozinku kako bi ste potvrdili da želite da uklonite nalog') }}.

                <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-jet-input type="password" class="mt-1 block w-3/4" placeholder="{{ __('Lozinka') }}"
                                x-ref="password"
                                wire:model.defer="password"
                                wire:keydown.enter="deleteUser" />

                    <x-jet-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                    {{ __('Odustani') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="deleteUser" wire:loading.attr="disabled">
                    {{ __('Ukloni nalog') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-dialog-modal>
    </x-slot>
</x-jet-action-section>
