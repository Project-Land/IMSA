<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Izmena cilja') }} 
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
            <a class="btn btn-light" href="{{ route('goals.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto md:w-4/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('goals.update', $goal->id) }}" method="POST" autocomplete="off"  class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="year">Godina</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="year" name="year">
                        @foreach(range(date("Y")-1, date("Y")+10) as $year)
                            <option value="{{ $year }}" {{ $year == $goal->year ? "selected" : "" }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group col-md-6">
                    <label for="responsibility">Odgovornost</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="responsibility" id="responsibility" value="{{ $goal->responsibility }}">
                    @error('responsibility')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>
                
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="goal">Cilj</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="goal" id="goal" value="{{ $goal->goal }}">
                    @error('goal')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="deadline">Rok za realizaciju</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="deadline" id="deadline" value="{{ date('d.m.Y', strtotime($goal->deadline)) }}">
                    @error('deadline')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="kpi">KPI</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="kpi" id="kpi" value="{{ $goal->kpi }}">
                    @error('kpi')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="resources">Resursi</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="resources" id="resources" value="{{ $goal->resources }}">
                    @error('resources')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="activities">Aktivnosti</label>
                    <textarea rows="10" style="height:200px;" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="activities" id="activities">{{ $goal->activities }}</textarea>
                    @error('activities')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="analysis">Analiza</label>
                    <textarea rows="10" style="height:200px;" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="analysis" id="analysis" {{ $goal->deadline <= date('Y-m-d') ? "required":"disabled" }} >{{ $goal->analysis }}</textarea>
                </div>
            </div>
            
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">Izmeni</button>
        </form>
    </div>

</x-app-layout>

<script>
    $.datetimepicker.setLocale('sr');
    $('#deadline').datetimepicker({
		timepicker: false,
		format:'d.m.Y',
        minDate: 0,
		dayOfWeekStart: 1,
        scrollInput: false
	});
</script>