<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Plan za postupanje sa rizikom / prilikom') }} - {{ __('Kreiranje / izmena plana') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('risk-management.index') }}"><i class="fas fa-arrow-left"></i> {{ __('Nazad') }}</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

        <div class="row">
            <div class="col">
                <p class="font-bold bg-white text-center text-lg shadow-md rounded px-8 pt-2 pb-2">{{ $risk->measure }} - {{ date('d.m.Y', strtotime($risk->measure_created_at)) }}</p>
            </div>
        </div>

		<form action="{{ route('risk-management.update-plan', $risk->id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf
			@method('PUT')

			<div class="mb-4">
				<label for="cause">{{ __('Uzrok') }}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cause" name="cause" value="{{ $risk->cause }}" autofocus required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
				@error('cause')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

            <div class="mb-4">
				<label for="risk_lowering_measure">{{ __('Mera za smanjenje rizika/ korišćenje prilike') }}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="risk_lowering_measure" name="risk_lowering_measure" value="{{ $risk->risk_lowering_measure }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
				@error('risk_lowering_measure')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

            <div class="mb-4">
				<label for="responsibility">{{ __('Odgovornost') }}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="responsibility" name="responsibility" value="{{ $risk->responsibility }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
				@error('responsibility')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

            <div class="mb-4">
				<label for="deadline">{{ __('Rok za realizaciju') }}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="deadline" name="deadline" value="{{ $risk->deadline != null ? date('d.m.Y', strtotime($risk->deadline)) : date('d.m.Y') }}" required oninvalid="this.setCustomValidity('{{ __("Izaberite datum") }}')" oninput="this.setCustomValidity('')" onchange="this.setCustomValidity('')">
				@error('deadline')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

            <div class="mb-4">
				<label for="status">{{ __('Status') }}:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="status" id="status">
                    <option value="1" {{ ($risk->status === 1 || $risk->status == null)? "selected" : "" }} >{{ __('Otvorena') }}</option>
                    <option value="0" {{ ($risk->status === 0)? "selected" : "" }} >{{ __('Zatvorena') }}</option>
                </select>
			</div>

			<button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{ __('Sačuvaj') }}</button>
		</form>
    </div>

</x-app-layout>

<script>
    var lang = document.getElementsByTagName('html')[0].getAttribute('lang');
    $.datetimepicker.setLocale(lang);

    $('#deadline').datetimepicker({
		timepicker: false,
		format:'d.m.Y',
		minDate: 0,
		dayOfWeekStart: 1,
		scrollInput: false
	});
</script>
