<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Kreiranje cilja') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
            <a class="btn btn-light" href="{{ route('goals.index') }}"><i class="fas fa-arrow-left"></i> {{ __('Nazad') }}</a>
     	</div>
    </div>

    <div class="mx-auto md:w-4/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('goals.store') }}" method="POST" autocomplete="off"  class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="year" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Godina') }}</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="year" name="year" required oninvalid="this.setCustomValidity('{{ __("Izaberite godinu") }}')" oninput="this.setCustomValidity('')">
                        @foreach(range(2019, date("Y")+10) as $year)
                            <option value="{{ $year }}" {{ $year == date('Y') ? "selected" : "" }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="responsibility" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Odgovornost za praćenje i realizaciju cilja') }}</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="responsibility" id="responsibility" value="{{ old('responsibility') }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
                    @error('responsibility')
					    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				    @enderror
                </div>

            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="goal" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Cilj') }}</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="goal" id="goal" value="{{ old('goal') }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
                    @error('goal')
					    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="deadline" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Rok za realizaciju') }}</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="xx.xx.xxxx" name="deadline" id="deadline" value="{{ old('deadline') }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')" onchange="this.setCustomValidity('')">
                    @error('deadline')
					    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="kpi" class="block text-gray-700 text-sm font-bold mb-2">{{ __('KPI') }}</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="kpi" id="kpi" value="{{ old('kpi') }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
                    @error('kpi')
					    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="resources" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Resursi') }}</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="resources" id="resources" value="{{ old('resources') }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
                    @error('resources')
					    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="activities" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Aktivnosti') }}</label>
                    <textarea rows="10" style="height:200px;" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="activities" id="activities" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">{{ old('activities') }}</textarea>
                    @error('activities')
					    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="analysis" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Analiza') }}</label>
                    <textarea rows="10" style="height:200px;" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="analysis" id="analysis" disabled>{{ old('analysis') }}</textarea>
                </div>
            </div>

            <button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{ __('Kreiraj') }}</button>
        </form>
    </div>

</x-app-layout>

<script>
	var lang = document.getElementsByTagName('html')[0].getAttribute('lang');
    $.datetimepicker.setLocale(lang);

    $('#deadline').datetimepicker({
		timepicker: false,
		format: 'd.m.Y',
        minDate: 0,
		dayOfWeekStart: 1,
        scrollInput: false
	});
</script>
