<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Upravljanje rizicima') }}  - {{ __('Kreiranje') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('risk-management.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('risk-management.store') }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf
			<div class="form-group">
				<label for="description" class="block text-gray-700 text-sm font-bold mb-2">Opis:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" autofocus></textarea>
				@error('description')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="probability" class="block text-gray-700 text-sm font-bold mb-2">Verovatnoća:</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="probability" id="probability">
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
			</div>
            <div class="form-group">
				<label for="frequency" class="block text-gray-700 text-sm font-bold mb-2">Učestalost:</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="frequency" id="frequency">
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
			</div>
            <div class="form-group">
				<label for="total" class="block text-gray-700 text-sm font-bold mb-2">Ukupno:</label>
				<input class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" name="total" id="total" disabled value="1">
			</div>
            <div class="form-group">
				<label for="acceptable" class="block text-gray-700 text-sm font-bold mb-2">Prihvatljivo:</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="acceptable" id="acceptable">
                    @for($i = 1; $i <= 25; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
			</div>
			<button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">Kreiraj</button>
		</form>
    </div>

</x-app-layout>

<script>

    function calculate(){
        let probability = $('#probability').val();
        let frequency = $('#frequency').val();
        let total = probability * frequency;
        $('#total').val(total);
    }

    $('#probability').change( () => {
        calculate();
    })

    $('#frequency').change(() => {
        calculate();
    })

</script>