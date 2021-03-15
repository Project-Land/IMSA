<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Izmena cilja') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
            <a class="btn btn-light" href="{{ route('goals.index') }}"><i class="fas fa-arrow-left"></i> {{ __('Nazad') }}</a>
     	</div>
    </div>

    <div class="mx-auto md:w-4/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('goals.update', $goal->id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="year" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Godina') }}</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="year" name="year" required oninvalid="this.setCustomValidity('{{ __("Izaberite godinu") }}')" oninput="this.setCustomValidity('')">
                        @foreach(range(2019, date("Y")+10) as $year)
                            <option value="{{ $year }}" {{ $year == $goal->year ? "selected" : "" }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label for="level" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Nivo važnosti') }}</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="level" name="level" required oninvalid="this.setCustomValidity('{{ __("Izaberite nivo") }}')" oninput="this.setCustomValidity('')">
                        <option value="" >{{ __('Izaberite nivo') }}</option>
                        <option value="1" @if($goal->level == '1'){{'selected'}} @endif >{{ __('Mali') }}</option>
                        <option value="2" @if($goal->level == '2'){{'selected'}} @endif >{{ __('Srednji') }}</option>
                        <option value="3" @if($goal->level == '3'){{'selected'}} @endif >{{ __('Veliki') }}</option>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="responsibility" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Odgovornost za praćenje i realizaciju cilja') }}</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="responsibility" id="responsibility" value="{{ $goal->responsibility }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
                    @error('responsibility')
					    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="goal">{{ __('Cilj') }}</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="goal" id="goal" value="{{ $goal->goal }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
                    @error('goal')
					    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="deadline" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Rok za realizaciju cilja') }}</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="xx.xx.xxxx" name="deadline" id="deadline" value="{{ date('d.m.Y', strtotime($goal->deadline)) }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
                    @error('deadline')
					    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				    @enderror
                </div>
                
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="kpi" class="block text-gray-700 text-sm font-bold mb-2">{{ __('KPI') }}</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="kpi" id="kpi" value="{{ $goal->kpi }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
                    @error('kpi')
					    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="resources" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Resursi') }}</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="resources" id="resources" value="{{ $goal->resources }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
                    @error('resources')
					    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				    @enderror
                </div>
            </div>
            

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="activities" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Aktivnosti') }}</label>
                    <textarea rows="10" style="height:200px;" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="activities" id="activities" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">{{ $goal->activities }}</textarea>
                    @error('activities')
					    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				    @enderror
                </div>
                <div class="form-group col-md-6">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Da li je cilj ispunjen') }}</label>
                            <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="status" name="status" {{ $goal->deadline <= date('Y-m-d') ? "required" : "disabled" }}>
                                <option value="0" {{ $goal->status == 0 ? "selected":"" }}>{{ __('Ne') }}</option>
                                <option value="1" {{ $goal->status == 1 ? "selected":"" }}>{{ __('Da') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="analysis" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Analiza') }}</label>
                            <textarea rows="5" class="h-28 appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="analysis" id="analysis" oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')" {{ $goal->deadline <= date('Y-m-d') ? "" : "disabled" }} >{{ $goal->analysis }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{ __('Izmeni') }}</button>
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
