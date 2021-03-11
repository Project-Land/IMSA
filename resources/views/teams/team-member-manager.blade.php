<div>

    <!-- @if (Gate::check('addTeamMember', $team))
        <x-jet-section-border />


        <div class="mt-10 sm:mt-0">
            <x-jet-form-section submit="addTeamMember">
                <x-slot name="title">
                    {{ __('Dodavanje članova tima') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Dodajte novog člana tima') }}
                </x-slot>

                <x-slot name="form">
                    <div class="col-span-6">
                        <div class="max-w-xl text-sm text-gray-600">
                            {{ __('Unesite email adresu korisnika kojeg želite da dodate u tim. Email adresa mora biti sa postojećeg naloga.') }}
                        </div>
                    </div>


                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label for="email" value="{{ __('Email') }}" />
                        <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="addTeamMemberForm.email" />
                        <x-jet-input-error for="email" class="mt-2" />
                    </div>


                    @if (count($this->roles) > 0)
                        <div class="col-span-6 lg:col-span-4">
                            <x-jet-label for="role" value="{{ __('Uloga') }}" />
                            <x-jet-input-error for="role" class="mt-2" />

                            <div class="mt-1 border border-gray-200 rounded-lg cursor-pointer">
                                @foreach ($this->roles as $index => $role)
                                    @if($role->name != "Super Admin")
                                        <div class="px-4 py-3 {{ $index > 0 ? 'border-t border-gray-200' : '' }}"
                                                        wire:click="$set('addTeamMemberForm.role', '{{ $role->key }}')">
                                            <div class="{{ isset($addTeamMemberForm['role']) && $addTeamMemberForm['role'] !== $role->key ? 'opacity-50' : '' }}">

                                                <div class="flex items-center">
                                                    <div class="text-sm text-gray-600 {{ $addTeamMemberForm['role'] == $role->key ? 'font-semibold' : '' }}">
                                                        {{ $role->name }}
                                                    </div>

                                                    @if ($addTeamMemberForm['role'] == $role->key)
                                                        <svg class="ml-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    @endif
                                                </div>


                                                <div class="mt-2 text-xs text-gray-600">
                                                    {{ $role->description }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </x-slot>

                <x-slot name="actions">
                    <x-jet-action-message class="mr-3" on="saved">
                        {{ __('Dodato.') }}
                    </x-jet-action-message>

                    <x-jet-button>
                        {{ __('Dodaj') }}
                    </x-jet-button>
                </x-slot>
            </x-jet-form-section>
        </div>
    @endif -->

    @if ($team->users->isNotEmpty())

        <!-- Manage Team Members -->
        <div class="mt-10 sm:mt-0">
            <x-jet-action-section>
                <x-slot name="title">
                    {{ __('Članovi firme') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Svi korisnički nalozi u ovkiru firme') }}.
                </x-slot>

                <!-- Team Member List -->
                <x-slot name="content">
                    <div class="space-y-6">
                        @foreach ($team->users->sortBy('name') as $user)
                            <div class="flex items-center flex-col sm:flex-row">
                                <div class="flex items-center w-full sm:w-1/2">
                                    <img class="w-8 h-8 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                    <div class="ml-4">{{ $user->name }}</div>
                                </div>

                                <div class="flex items-center w-full sm:w-1/2 justify-end border-b sm:border-none">
                                    <!-- Manage Team Member Role -->
                                    <div class="w-full sm:w-28">
                                        @if(Laravel\Jetstream\Jetstream::findRole($user->membership->role)->name != "Korisnik")
                                            <button class="ml-2 text-sm text-gray-400 underline" onclick="toggleModal('{{ $user->id }}')">
                                                {{ __('Sertifikati') }}
                                            </button>
                                        @endif
                                    </div>

                                    <div class="w-full sm:w-28 text-center">
                                        @if (Gate::check('addTeamMember', $team) && Laravel\Jetstream\Jetstream::hasRoles())
                                            <button class="ml-2 text-sm text-gray-400 underline" @if(Laravel\Jetstream\Jetstream::findRole($user->membership->role)->name != "Super Admin" && $user->id != $this->user->id) wire:click="manageRole('{{ $user->id }}')" @endif>
                                                {{ __(Laravel\Jetstream\Jetstream::findRole($user->membership->role)->name) }}
                                            </button>
                                        @elseif (Laravel\Jetstream\Jetstream::hasRoles())
                                            <div class="ml-2 text-sm text-gray-400">
                                                {{ Laravel\Jetstream\Jetstream::findRole($user->membership->role)->name }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- <div class="w-full sm:w-24">

                                        @if ($this->user->id === $user->id && Laravel\Jetstream\Jetstream::findRole($user->membership->role)->name != "Super Admin")
                                            <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="$toggle('confirmingLeavingTeam')">
                                                {{ __('Napusti') }}
                                            </button>


                                        @elseif (Gate::check('removeTeamMember', $team) && Laravel\Jetstream\Jetstream::findRole($user->membership->role)->name != "Super Admin")
                                            <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmTeamMemberRemoval('{{ $user->id }}')">
                                                {{ __('Ukloni') }}
                                            </button>
                                        @endif
                                    </div>-->
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-slot>
            </x-jet-action-section>
        </div>
    @endif

    <!-- Role Management Modal -->
    <x-jet-dialog-modal wire:model="currentlyManagingRole">
        <x-slot name="title">
            {{ __('Upravljaj ulogama') }}
        </x-slot>

        <x-slot name="content">
                <div class="mt-1 border border-gray-200 rounded-lg cursor-pointer">
                    @foreach ($this->roles as $index => $role)
                        @if($role->name != "Super Admin")
                            <div class="px-4 py-3 {{ $index > 0 ? 'border-t border-gray-200' : '' }}"
                                            wire:click="$set('currentRole', '{{ $role->key }}')">
                                <div class="{{ $currentRole !== $role->key ? 'opacity-50' : '' }}">
                                    <!-- Role Name -->
                                    <div class="flex items-center">
                                        <div class="text-sm text-gray-600 {{ $currentRole == $role->key ? 'font-semibold' : '' }}">
                                            {{ __($role->name) }}
                                        </div>

                                        @if ($currentRole == $role->key)
                                            <svg class="ml-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @endif
                                    </div>

                                    <!-- Role Description -->
                                    <div class="mt-2 text-xs text-gray-600">
                                        {{ __($role->description) }}
                                    </div>
                                </div>
                            </div>
                        @endif
                @endforeach
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="stopManagingRole" wire:loading.attr="disabled">
                {{ __('Odustani') }}
            </x-jet-secondary-button>

            <x-jet-button class="ml-2" wire:click="updateRole" wire:loading.attr="disabled">
                {{ __('Sačuvaj') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>

    <!-- Leave Team Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingLeavingTeam">
        <x-slot name="title">
            {{ __('Ukloni korisnički nalog iz firme') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Da li ste sigurni da želite da uklonite korisnički nalog iz ove firme?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingLeavingTeam')" wire:loading.attr="disabled">
                {{ __('Odustani') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="leaveTeam" wire:loading.attr="disabled">
                {{ __('Napusti') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>

    <!-- Remove Team Member Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingTeamMemberRemoval">
        <x-slot name="title">
            {{ __('Ukloni korisnički nalog iz firme') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Da li ste sigurni da želite da uklonite ovaj korisnički nalog iz firme?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingTeamMemberRemoval')" wire:loading.attr="disabled">
                {{ __('Odustani') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="removeTeamMember" wire:loading.attr="disabled">
                {{ __('Ukloni') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>

</div>



<script>

    let certificates = [];

    axios.get('/certificates')
        .then((response) => {
            $.each(response.data, function (i, item){
                certificates.push(item)
            })
        })
        .catch((error) => {
            console.log(error)
        });

    function toggleModal(modalID){
        axios.get('/user/'+modalID+'/certificates')
        .then((response) => {
            sessionStorage.setItem('selectedCertificates', response.data)
        })
        .then((resp) => {
            let selectedCertificates = sessionStorage.getItem('selectedCertificates').split(',');

            sessionStorage.clear();

            let modal = `<div class="modal fade" id="certificates-${ modalID }" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="text-lg">
                                            {{ __('Upravljaj sertifikatima') }}
                                        </div>
                                        <div class="mt-4">
                                            <div class="mt-1 border border-gray-200 rounded-lg">
                                                ${ Object.keys(certificates).map(key => (
                                                    `<div class="px-4 py-3 border-t border-gray-200">
                                                        <label class="inline-flex items-center mt-3 cursor-pointer">
                                                            <input type="checkbox" name="certificates[]" value="${ certificates[key].id }" class="form-checkbox h-5 w-5 text-gray-600" ${ selectedCertificates.includes(certificates[key].id.toString()) ? 'checked':'' }><span class="ml-2 text-gray-700">${ certificates[key].name }</span>
                                                        </label>
                                                    </div>`
                                                )).join('') }
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 bg-gray-100 text-right">
                                        <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Odustani') }}</button>
                                        <button type="button" onclick="updateUser(${ modalID })" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150 ml-2" data-dismiss="modal">{{ __('Sačuvaj') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>`;

            $("body").append(modal);
            $('#certificates-'+modalID).modal();
        })
        .catch((error) => {
            console.log(error)
        });
    }

    function updateUser(id){
        var selecteditems = [];

        $("#certificates-"+id).find("input:checked").each(function (i, ob) {
            selecteditems.push($(ob).val());
        });

        axios.post('/update-user-certificates/'+id, {
            selecteditems
        })
        .then((response) => {
            //console.log(response)
        })
        .catch((error) => {
            console.log(error)
        });
    }
</script>
