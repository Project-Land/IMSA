<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ __('Dodavanje sistemskog procesa za standard') }}
        </h2>
    </x-slot>

    <div class="row mt-1">
        <div class="col">
            @if(Session::has('status'))
                <x-alert :type="Session::get('status')[0]" :message="Session::get('status')[1]"/>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        <div class="md:grid md:grid-cols-3 md:gap-6">

            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Dodavanje sistemskog procesa za standard') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Dodajte novi sistemski proces za izabrani standard') }}
                    </p>
                </div>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-2">

                <form method="POST" action="{{ route('system-processes.store-to-standard') }}" autocomplete="off">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">

                        <div class="px-4 py-3 bg-white sm:p-6">
                            <x-jet-label for="standard" value="{{ __('Standard') }}" class="block font-medium text-sm text-gray-700" />
                            <select class="block mt-1 appearance-none w-full border border-gray-700 font-small text-sm text-gray-700 py-2 px-2 pr-8 rounded-md shadow-sm focus:outline-none focus:bg-white focus:border-gray-500" name="standard" id="standard" required>
                                <option value="0">{{ __('Izaberi') }}...</option>
                                @foreach($standards as $standard)
                                    <option value="{{ $standard->id }}">{{ $standard->name }}</option>
                                @endforeach
                            </select>
                            @error('standard')
                                <p class="text-sm text-red-600 mt-2">{{ __($message) }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-3 bg-white sm:p-6">
                            <x-jet-label for="system_process" value="{{ __('Sistemski proces') }}" class="block font-medium text-sm text-gray-700" />
                            <select class="block mt-1 appearance-none w-full border border-gray-700 font-small text-sm text-gray-700 py-2 px-2 pr-8 rounded-md shadow-sm focus:outline-none focus:bg-white focus:border-gray-500" name="system_process" id="system_process" required>
                                <option value="0">{{ __('Izaberi') }}...</option>
                            </select>
                            @error('system_process')
                                <p class="text-sm text-red-600 mt-2">{{ __($message) }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-jet-button class="ml-4 mb-4 mr-4" id="add-button">
                                {{ __('Dodaj') }}
                            </x-jet-button>
                        </div>

                    </div>
                </form>
            </div>

        </div>

        <x-jet-section-border />

    </div>

</x-app-layout>

<script>
    $('#add-button').prop('disabled', true);

    $('#standard').change( () => {
        let standard = $('#standard').val();
        $('#add-button').prop('disabled', false);
        $('#system_process').prop('disabled', false);

        const data = {'standard': standard}

        if(standard != 0){
            axios.post('/system-processes/get-by-standard', { data })
            .then((response) => {
                if(response.data.length == 0){
                    $('#system_process').html('<option value="" selected>{{ __("Nema dostupnih sistemskih procesa") }}</option>');
                    $('#system_process').prop('disabled', true);
                    $('#add-button').prop('disabled', true);
                }
                else{
                    let allData = "";
                    $.each(response.data, function (i, item){
                        let option = `<option value="${ item.id }">{{ __('${ item.name }') }}</option>`;
                        allData += option;
                    });
                    $('#system_process').html(allData)
                }
            }, (error) => {
                console.log(error);
            })
        }
        else{
            $('#add-button').prop('disabled', true);
        }
    });
</script>
