<x-app-layout>
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js" integrity="sha512-UdIMMlVx0HEynClOIFSyOrPggomfhBKJE28LKl8yR3ghkgugPnG6iLfRfHwushZl1MOPSY6TsuBDGPK2X4zYKg==" crossorigin="anonymous"></script>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Upravljanje rizicima') }} - {{ __('Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('risk-management.index') }}"><i class="fas fa-arrow-left"></i> {{ __('Nazad') }}</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form id="risk-management-edit" action="{{ route('risk-management.update', $risk->id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf
            @method('PUT')

			<div class="mb-4">
				<label for="description" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Opis') }}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" autofocus required>{{ $risk->description }}</textarea>
				@error('description')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
            </div>

			<div class="mb-4">
				<label for="probability" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Verovatnoća') }}:</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="probability" id="probability" required oninvalid="this.setCustomValidity({{ __('Popunite polje') }})" oninput="this.setCustomValidity('')">
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ ($i == $risk->probability) ? 'selected' : '' }} >{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="mb-4">
				<label for="frequency" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Posledice') }}:</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="frequency" id="frequency" required oninvalid="this.setCustomValidity({{ __('Popunite polje') }})" oninput="this.setCustomValidity('')">
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ ($i == $risk->frequency) ? 'selected' : '' }} >{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="mb-4">
				<label for="total" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Ukupno') }}:</label>
				<input class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" name="total" id="total" disabled value="{{ $risk->total }}">
            </div>

            <div class="mb-4">
				<label for="acceptable" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Prihvatljivo') }}:</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="acceptable" id="acceptable" required oninvalid="this.setCustomValidity({{ __('Popunite polje') }})" oninput="this.setCustomValidity('')">
                    @for($i = 1; $i <= 25; $i++)
                        <option value="{{ $i }}" {{ ($i == $risk->acceptable) ? 'selected' : '' }} >{{ $i }}</option>
                    @endfor
                </select>
            </div>

			<button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{ __('Izmeni') }}</button>
		</form>
    </div>

    @push('page-scripts')
        <script>
            $("#risk-management-edit").validate({
                messages: {
                    description: "{{ __('Popunite polje') }}",
                }
            });

            function calculate(){
                let probability = $('#probability').val();
                let frequency = $('#frequency').val();
                let total = probability * frequency;
                $('#total').val(total);
            }

            $('#probability').change( () => {
                calculate();
            })

            $('#frequency').change( () => {
                calculate();
            })
        </script>
    @endpush

</x-app-layout>
