<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Aspekt životne sredine - Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('environmental-aspects.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('environmental-aspects.update', $environmental_aspect->id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf
            @method('PUT')

			<div class="mb-4">
				<label for="process" class="block text-gray-700 text-sm font-bold mb-2">Proces:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="process" name="process" value="{{ $environmental_aspect->process }}" autofocus required oninvalid="this.setCustomValidity('Popunite polje')" oninput="this.setCustomValidity('')">
				@error('process')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>

            <div class="mb-4">
				<label for="waste" class="block text-gray-700 text-sm font-bold mb-2">Otpad / Fizičko-hemijska pojava:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="waste" name="waste" value="{{ $environmental_aspect->waste }}" required oninvalid="this.setCustomValidity('Popunite polje')" oninput="this.setCustomValidity('')">
				@error('waste')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>

            <div class="mb-4">
				<label for="aspect" class="block text-gray-700 text-sm font-bold mb-2">Aspekt:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="aspect" name="aspect" value="{{ $environmental_aspect->aspect }}" required oninvalid="this.setCustomValidity('Popunite polje')" oninput="this.setCustomValidity('')">
				@error('aspect')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>

            <div class="mb-4">
				<label for="influence" class="block text-gray-700 text-sm font-bold mb-2">Uticaj:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="influence" name="influence" value="{{ $environmental_aspect->influence }}" required oninvalid="this.setCustomValidity('Popunite polje')" oninput="this.setCustomValidity('')">
				@error('influence')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>

            <div class="mb-4">
				<label for="waste_type" class="block text-gray-700 text-sm font-bold mb-2">Karakter otpada:</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="waste_type" id="waste_type" required>
                    <option value="1" {{ $environmental_aspect->waste_type === 1 ? "selected" : "" }}>Opasan</option>
                    <option value="0" {{ $environmental_aspect->waste_type === 0 ? "selected" : "" }}>Neopasan</option>
                </select>
            </div>

			<div class="mb-4">
				<label for="probability_of_appearance" class="block text-gray-700 text-sm font-bold mb-2">Verovatnoća pojavljivanja:</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="probability_of_appearance" id="probability_of_appearance" required>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ $environmental_aspect->probability_of_appearance === $i ? "selected" : "" }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="mb-4">
				<label for="probability_of_discovery" class="block text-gray-700 text-sm font-bold mb-2">Verovatnoća otkrivanja:</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="probability_of_discovery" id="probability_of_discovery" required>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ $environmental_aspect->probability_of_discovery === $i ? "selected" : "" }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="mb-4">
				<label for="severity_of_consequences" class="block text-gray-700 text-sm font-bold mb-2">Ozbiljnost posledica:</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="severity_of_consequences" id="severity_of_consequences" required>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ $environmental_aspect->severity_of_consequences === $i ? "selected" : "" }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="mb-4">
				<label for="estimated_impact" class="block text-gray-700 text-sm font-bold mb-2">Procenjeni uticaj:</label>
				<input class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline {{ $environmental_aspect->estimated_impact >= 8 ? "text-red-700" : "" }}" type="text" name="estimated_impact" id="estimated_impact" value="{{ $environmental_aspect->estimated_impact }}" disabled>
            </div>

			<button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">Izmeni</button>
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
