<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Kreiranje cilja') }} 
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
            <a class="btn btn-light" href="{{ route('goals.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto w-75 mt-10 bg-secondary p-10 rounded">

		<form action="{{ route('goals.store') }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="year">Godina</label>
                    <select class="form-control" id="year" name="year">
                        @foreach(range(date("Y")-1, date("Y")+10) as $year)
                            <option value="{{ $year }}" {{ $year == date('Y') ? "selected" : "" }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group col-md-6">
                    <label for="responsibility">Odgovornost</label>
                    <input type="text" class="form-control" name="responsibility" id="responsibility" value="{{ old('responsibility') }}">
                    @error('responsibility')
					    <span class="text-danger">{{ $message }}</span>
				    @enderror
                </div>
                
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="goal">Cilj</label>
                    <input type="text" class="form-control" name="goal" id="goal" value="{{ old('goal') }}">
                    @error('goal')
					    <span class="text-danger">{{ $message }}</span>
				    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="deadline">Rok za realizaciju</label>
                    <input type="text" class="form-control" name="deadline" id="deadline" value="{{ old('deadline') }}">
                    @error('deadline')
					    <span class="text-danger">{{ $message }}</span>
				    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="kpi">KPI</label>
                    <input type="text" class="form-control" name="kpi" id="kpi" value="{{ old('kpi') }}">
                    @error('kpi')
					    <span class="text-danger">{{ $message }}</span>
				    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="resources">Resursi</label>
                    <input type="text" class="form-control" name="resources" id="resources" value="{{ old('resources') }}">
                    @error('resources')
					    <span class="text-danger">{{ $message }}</span>
				    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="activities">Aktivnosti</label>
                    <textarea rows="10" style="height:200px;" class="form-control" name="activities" id="activities">{{ old('activities') }}</textarea>
                    @error('activities')
					    <span class="text-danger">{{ $message }}</span>
				    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="analysis">Analiza</label>
                    <textarea rows="10" style="height:200px;" class="form-control" name="analysis" id="analysis" disabled>{{ old('analysis') }}</textarea>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Kreairaj</button>
        </form>
    </div>

</x-app-layout>

<script>
	$.datetimepicker.setLocale('sr');
    $('#deadline').datetimepicker({
		timepicker: false,
		format:'d.m.Y'
	});
</script>