<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Aspekti životne sredine')}} - {{__('Kreiranje') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('environmental-aspects.index') }}"><i class="fas fa-arrow-left"></i> {{__('Nazad')}}</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('environmental-aspects.store') }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

			<div class="mb-4">
				<label for="process" class="block text-gray-700 text-sm font-bold mb-2">{{__('Proces')}}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="process" name="process" autofocus required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')">
				@error('process')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>

            <div class="mb-4">
				<label for="waste" class="block text-gray-700 text-sm font-bold mb-2">{{__('Otpad / Fizičko-hemijska pojava')}}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="waste" name="waste" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')">
				@error('waste')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>

            <div class="mb-4">
				<label for="aspect" class="block text-gray-700 text-sm font-bold mb-2">{{__('Aspekt')}}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="aspect" name="aspect" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')">
				@error('aspect')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>

            <div class="mb-4">
				<label for="influence" class="block text-gray-700 text-sm font-bold mb-2">{{__('Uticaj')}}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="influence" name="influence" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')">
				@error('influence')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>

            <div class="mb-4">
				<label for="waste_type" class="block text-gray-700 text-sm font-bold mb-2">{{__('Karakter otpada')}}:</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="waste_type" id="waste_type" required>
                    <option value="1">{{__('Opasan')}}</option>
                    <option value="0">{{__('Neopasan')}}</option>
                </select>
            </div>

			<div class="mb-4">
				<label for="probability_of_appearance" class="block text-gray-700 text-sm font-bold mb-2">{{__('Verovatnoća pojavljivanja')}}:</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="probability_of_appearance" id="probability_of_appearance" required>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="mb-4">
				<label for="probability_of_discovery" class="block text-gray-700 text-sm font-bold mb-2">{{__('Verovatnoća otkrivanja')}}:</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="probability_of_discovery" id="probability_of_discovery" required>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="mb-4">
				<label for="severity_of_consequences" class="block text-gray-700 text-sm font-bold mb-2">{{__('Ozbiljnost posledica')}}:</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="severity_of_consequences" id="severity_of_consequences" required>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="mb-4">
				<label for="estimated_impact" class="block text-gray-700 text-sm font-bold mb-2">{{__('Procenjeni uticaj')}}:</label>
				<input class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" name="estimated_impact" id="estimated_impact" value="3" disabled>
            </div>

			<button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{__('Kreiraj')}}</button>
		</form>
    </div>

</x-app-layout>

<script>

    function calculate(){
        let poa = $('#probability_of_appearance').val();
        let pod = $('#probability_of_discovery').val();
        let soc = $('#severity_of_consequences').val();
        let total = parseInt(poa) +parseInt(pod) + parseInt(soc);
        $('#estimated_impact').val(total);
        if(total >= 8){
            $('#estimated_impact').addClass('text-red-700');
        }
        else{
            $('#estimated_impact').removeClass('text-red-700');
        }

    }

    $('#probability_of_appearance').change( () => {
        calculate();
    })

    $('#probability_of_discovery').change(() => {
        calculate();
    })

    $('#severity_of_consequences').change(() => {
        calculate();
    })

</script>
