<x-jet-form-section submit="createTeam">
    <x-slot name="title">
        {{ __('Firma') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Kreiraj novu firmu') }}
    </x-slot>

    <x-slot name="form">
        <!--
        <div class="col-span-6">
            <x-jet-label value="{{ __('Vlasnik firme') }}" />

            <div class="flex items-center mt-2">
                <img class="w-12 h-12 rounded-full object-cover" src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}">

                <div class="ml-4 leading-tight">
                    <div>{{ $this->user->name }}</div>
                    <div class="text-gray-700 text-sm">{{ $this->user->email }}</div>
                </div>
            </div>
        </div> -->

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('Naziv firme') }}" />
            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="state.name" autofocus />
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

            <x-jet-label for="logo" value="{{ __('Logo') }}" />

            <!--Logo Preview -->
            <div class="mt-2" x-show="photoPreview">
                <span class="block rounded-full w-20 h-20"
                      x-bind:style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                </span>
            </div>

            <div wire:loading wire:target="logo">{{ __('Postavlja se') }}...</div>

            <x-jet-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                {{ __('Izaberite logo') }}
            </x-jet-secondary-button>

            <x-jet-input-error for="logo" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-button>
            {{ __('Kreiraj') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
