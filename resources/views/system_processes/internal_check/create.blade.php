<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Interna provera') }} - {{ __('Kreiranje') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('internal-check.index') }}"><i class="fas fa-arrow-left"></i> {{ __('Nazad') }}</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded" >

		<form action="{{ route('internal-check.store') }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <div class="mb-4">
                <label for="date" class="block text-gray-700 text-sm font-bold mb-2">{{ __("Termin provere") }}</label>
                <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="date" placeholder="xx.xx.xxxx" name="date" required oninvalid="this.setCustomValidity('{{ __("Izaberite termin") }}')" oninput="this.setCustomValidity('')" onchange="this.setCustomValidity('')">
                @error('date')
                    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="sector_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Područje provere') }}</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="sector_id" name="sector_id" required oninvalid="this.setCustomValidity('{{ __("Izaberite sektor") }}')" oninput="this.setCustomValidity('')">
                    @foreach($sectors as $sector)
                        <option value="{{$sector->id}}">{{$sector->name}}</option>
                    @endforeach
                </select>
                @error('sector_id')
                    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="leaders" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Vođe tima') }}</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="leaders" name="leaders[]" multiple required oninvalid="this.setCustomValidity('{{ __("Izaberite proveravače") }}')" oninput="this.setCustomValidity('')">
                    @foreach($teamLeaders as $teamLeader)
                        <option value="{{ $teamLeader->name }}">{{ $teamLeader->name }}</option>
                    @endforeach
                </select>
                @error('leaders')
                    <span class="text-red-700 italic text-sm">{{ __($message) }}</span><br>
                @enderror
                <small class="text-gray-500">{{ __('Držite CTRL i birajte levim klikom miša') }}</small>
            </div>

            <div class="mb-4">
                <label for="standard_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Standard') }}</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="standard_id" name="standard_id" required oninvalid="this.setCustomValidity('{{ __("Izaberite standard") }}')" oninput="this.setCustomValidity('')">
                    <option value="{{ session('standard') }}">{{ session('standard_name') }}</option>
                </select>
                @error('standard_id')
                    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{ __('Kreiraj') }}</button>
        </form>
    </div>

    <script>
        var lang = document.getElementsByTagName('html')[0].getAttribute('lang');
        jQuery.datetimepicker.setLocale(lang);
        $('#date').datetimepicker({
            timepicker: false,
            format: 'd.m.Y',
            dayOfWeekStart: 1,
            minDate: 0,
            scrollInput: false
        });

        $('#status').change( () => {
            if($('#status').val() == 1){
                $('#final_num_field').css('display', '');
                $('#rating_field').css('display', '');
            }
            else{
                $('#final_num_field').css('display', 'none');
                $('#rating_field').css('display', 'none');
                $('#final_num_of_employees').val('');
                $('#rating').val('');
            }
        })
    </script>

</x-app-layout>
