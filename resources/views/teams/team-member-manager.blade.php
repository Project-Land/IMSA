<div>

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
                    <div class="space-y-6" wire:ignore>
                        <table class="stripe hover min-w-full divide-y divide-gray-200 yajra-datatable">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Ime') }}</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Dozvole pristupa') }}</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Uloga') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($team->users->sortBy('name') as $user)
                                    @if($user->membership->role == "super-admin") @continue @endif
                                    <tr wire:key="{{ $user->id }}">
                                        <td class="px-4 py-2 whitespace-no-wrap">
                                            <div class="flex items-center w-full sm:w-1/2">
                                                <img class="w-8 h-8 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                                <div class="ml-4">{{ $user->name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-no-wrap">
                                            <div class="w-full sm:w-28">
                                                @if($user->membership->role != "user")
                                                    <span class="cursor-pointer ml-2 text-sm text-gray-400" onclick="toggleModal('{{ $user->id }}')">
                                                        {{ __('Lista dozvola pristupa') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-no-wrap">
                                            @if(Gate::check('addTeamMember', $team) && Laravel\Jetstream\Jetstream::hasRoles())
                                                <span class="cursor-pointer ml-2 text-sm text-gray-400" @if(Laravel\Jetstream\Jetstream::findRole($user->membership->role)->name != "Super Admin" && $user->id != $this->user->id) wire:click="manageRole('{{ $user->id }}')" @endif>
                                                    {{ __(Laravel\Jetstream\Jetstream::findRole($user->membership->role)->name) }}
                                                </span>
                                            @elseif (Laravel\Jetstream\Jetstream::hasRoles())
                                                <div class="cursor-pointer ml-2 text-sm text-gray-400">
                                                    {{ Laravel\Jetstream\Jetstream::findRole($user->membership->role)->name }}
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

            <x-jet-button class="ml-2" wire:click="updateRole" wire:loading.attr="disabled" onclick="location.reload()">
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

@push('page-scripts')
<script>
    $('.yajra-datatable').DataTable({
        "language": {
            "info": "{{__('Prikaz strane')}} _PAGE_ {{__('od')}} _PAGES_",
            "infoEmpty": "{{__('Nema podataka')}}",
            "zeroRecords": "{{__('Nema podataka')}}",
            "infoFiltered": "({{__('od')}} _MAX_ {{__('ukupno rezultata')}})",
            "lengthMenu": "{{__('Prikaži')}} _MENU_ {{__('redova po stranici')}}",
            "search": "{{__('Pretraga')}}",
            "paginate": {
                "next": "{{__('Sledeća')}}",
                "previous": "{{__('Prethodna')}}",
                "first": "{{__('Prva')}}",
                "last": "{{__('Poslednja')}}"
            }
        },
        "columnDefs": [{
        "targets": 'no-sort',
        "orderable": false,
        }],
        "order": [[ 0, "asc" ]]
    });

    document.addEventListener('livewire:load', function (event) {
        window.reload
    });
</script>
@endpush

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
                                            {{ __('Upravljaj dozvolama pristupa') }}
                                        </div>
                                        <div class="mt-4">
                                            <div class="mt-1 border border-gray-200 rounded-lg">
                                                ${ Object.keys(certificates).map(key => (
                                                    `<div class="px-4 py-3 border-t border-gray-200">
                                                        <label class="inline-flex items-center mt-3 cursor-pointer">
                                                            <input type="checkbox" name="certificates[]" value="${ certificates[key].id }" class="form-checkbox h-5 w-5 text-gray-600" ${ selectedCertificates.includes(certificates[key].id.toString()) ? 'checked':'' }><span class="ml-2 text-gray-700">${ certificates[key].display_name }</span>
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
