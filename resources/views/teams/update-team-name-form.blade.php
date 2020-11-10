<x-jet-form-section submit="updateTeamName">
    <x-slot name="title">
        {{ __('Naziv Tima') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Naziv tima i informacije o vlasniku') }}
    </x-slot>

    <x-slot name="form">
        <!-- Team Owner Information -->
        <div class="col-span-6">
            <x-jet-label value="{{ __('Vlasnik Tima') }}" />

            <div class="flex items-center mt-2">
                <img class="w-12 h-12 rounded-full object-cover" src="{{ $team->owner->profile_photo_url }}" alt="{{ $team->owner->name }}">

                <div class="ml-4 leading-tight">
                    <div>{{ $team->owner->name }}</div>
                    <div class="text-gray-700 text-sm">{{ $team->owner->email }}</div>
                </div>
            </div>
        </div>

        <!-- Team Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('Naziv Tima') }}" />

            <x-jet-input id="name"
                        type="text"
                        class="mt-1 block w-full"
                        wire:model.defer="state.name"
                        :disabled="! Gate::check('update', $team)" />

            <x-jet-input-error for="name" class="mt-2" />
        </div>

        <!-- Logo File Input -->
        <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
            <input type="file" class="hidden"
                        wire:model.defer="state.logo"
                        x-ref="photo"
                        x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                        " />

            <x-jet-label for="photo" value="{{ __('Logo') }}" />

            <!--Logo Preview -->
            <div class="mt-2" x-show="photoPreview">
                <span class="block rounded-full w-20 h-20"
                      x-bind:style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                </span>
            </div>

            <!-- Current Profile Photo -->
            <div class="mt-2" x-show="! photoPreview">
                <img src="{{ asset('storage/logos/'.\Auth::user()->currentTeam->logo) }}" alt="" class="rounded-full h-20 w-20 object-cover">
            </div>

            <div wire:loading wire:target="logo">Postavlja se...</div>

            <x-jet-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                {{ __('Izaberite Novi Logo') }}
            </x-jet-secondary-button>

            <x-jet-input-error for="logo" class="mt-2" />
        </div>
    </x-slot>

    @if (Gate::check('update', $team))
        <x-slot name="actions">
            <x-jet-action-message class="mr-3" on="saved">
                {{ __('Sačuvano.') }}
            </x-jet-action-message>

            <x-jet-button>
                {{ __('Sačuvaj') }}
            </x-jet-button>
        </x-slot>
    @endif
</x-jet-form-section>
