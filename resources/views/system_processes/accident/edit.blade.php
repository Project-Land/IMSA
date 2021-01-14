<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Istraživanje incidenata') }}  - {{ __('Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('accidents.index') }}"><i class="fas fa-arrow-left"></i> {{__('Nazad')}}</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('accidents.update',$accident->id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf
			@method('PUT')

			<div class="mb-4">
				<label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{__('Prezime, ime povređenog')}}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" value="{{ $accident->name }}" autofocus required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                oninput="this.setCustomValidity('')">
				@error('name')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>
            
            <div class="mb-4">
				<label for="jobs_and_tasks_he_performs" class="block text-gray-700 text-sm font-bold mb-2">{{__('Poslovi i zadaci koje obavlјa')}}:</label>
                <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="jobs_and_tasks_he_performs" name="jobs_and_tasks_he_performs" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                oninput="this.setCustomValidity('')">{{ $accident->jobs_and_tasks_he_performs }}</textarea>
				@error('jobs_and_tasks_he_performs')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="injury_datetime" class="block text-gray-700 text-sm font-bold mb-2">{{__('Datum i vreme povrede')}}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="injury_datetime" name="injury_datetime" value="{{ date('d.m.Y H:i', strtotime($accident->injury_datetime)) }}" required oninvalid="this.setCustomValidity('{{__("Izaberite datum")}}')" oninput="this.setCustomValidity('')" onchange="this.setCustomValidity('')" placeholder="xx.xx.xxxx">
				@error('injury_datetime')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			
            <div class="mb-4">
				<label for="injury_type" class="block text-gray-700 text-sm font-bold mb-2">{{__('Tip povrede')}}:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="injury_type" name="injury_type" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                oninput="this.setCustomValidity('')">
                <option value="">{{__('Izaberi')}}...</option>
					<option value="mala" {{ $accident->injury_type == "mala" ? "selected" : "" }}>{{__('Mala')}}</option>
					<option value="velika" {{ $accident->injury_type == "velika" ? "selected" : "" }}>{{__('Velika')}}</option>
                </select>
                @error('injury_type')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="injury_cause" class="block text-gray-700 text-sm font-bold mb-2">{{__('Uzrok povrede')}}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="injury_cause" name="injury_cause"   required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                oninput="this.setCustomValidity('')" >{{ $accident->injury_cause }}</textarea>
				@error('injury_cause')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>
            
            <div class="mb-4">
				<label for="injury_description" class="block text-gray-700 text-sm font-bold mb-2">{!! __('KRATAK OPIS POVREDE <br>(kako je došlo do povrede – u fazama)') !!}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="injury_description" name="injury_description"  required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                oninput="this.setCustomValidity('')" >{{ $accident->injury_description }}</textarea>
				@error('injury_description')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

            <div class="mb-4">
				<label for="error" class="block text-gray-700 text-sm font-bold mb-2">{{__('Šta je greška?')}}</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="error" name="error"  required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                oninput="this.setCustomValidity('')" >{{ $accident->error }}</textarea>
				@error('error')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>
            
            <div class="mb-4">
				<label for="order_from" class="block text-gray-700 text-sm font-bold mb-2">{{__('Po čijem je nalogu radio?')}}</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="order_from" name="order_from" value="{{ $accident->order_from }}" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                oninput="this.setCustomValidity('')" >
				@error('order_from')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>
            
            <div class="mb-4">
				<label for="dangers_and_risks" class="block text-gray-700 text-sm font-bold mb-2">{{__('Da li je obučen za rad i upoznat sa opasnostima i rizicima za te poslove?')}}</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="dangers_and_risks" name="dangers_and_risks" value="{{ $accident->dangers_and_risks }}" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                oninput="this.setCustomValidity('')" >
				@error('dangers_and_risks')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>
            
            <div class="mb-4">
				<label for="protective_equipment" class="block text-gray-700 text-sm font-bold mb-2">{{__('Da li je koristio predviđena lična zaštitna sredstva i opremu i koju?')}}</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="protective_equipment" name="protective_equipment" value="{{ $accident->protective_equipment }}" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                oninput="this.setCustomValidity('')" >
				@error('protective_equipment')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>
            
            <div class="mb-4">
				<label for="high_risk_jobs" class="block text-gray-700 text-sm font-bold mb-2">{{__('Da li je radio na poslovima sa povećanim rizikom?')}}</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="high_risk_jobs" name="high_risk_jobs" value="{{ $accident->high_risk_jobs }}" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                oninput="this.setCustomValidity('')" >
				@error('high_risk_jobs')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>
            
            <div class="mb-4">
				<label for="job_requirements" class="block text-gray-700 text-sm font-bold mb-2">{{__('Da li ispunjava sve uslove za te poslove?')}}</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="job_requirements" name="job_requirements" value="{{ $accident->job_requirements }}" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                oninput="this.setCustomValidity('')" >
				@error('job_requirements')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>
            
            <div class="mb-4">
				<label for="witness" class="block text-gray-700 text-sm font-bold mb-2">{!! __('Podaci o svedoku-očevicu<br>– Ime prezime i broj telefona (ako je bilo)') !!}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="witness" name="witness"   >{{ $accident->witness }}</textarea>
				@error('witness')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>
            
            <div class="mb-4">
				<label for="supervisor" class="block text-gray-700 text-sm font-bold mb-2">{!! __('Podaci o neposrednom rukovodiocu povređenog<br>- Ime prezime i radno mesto') !!}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="supervisor" name="supervisor"  required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                oninput="this.setCustomValidity('')" >{{ $accident->supervisor }}</textarea>
				@error('supervisor')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>

            <div class="mb-4">
				<label for="injury_report_datetime" class="block text-gray-700 text-sm font-bold mb-2">{{__('Datum/vreme prijave povrede')}}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="injury_report_datetime" name="injury_report_datetime" value="{{ date('d.m.Y H:i', strtotime($accident->injury_report_datetime)) }}" required oninvalid="this.setCustomValidity('{{__("Izaberite datum")}}')" oninput="this.setCustomValidity('')" onchange="this.setCustomValidity('')" placeholder="xx.xx.xxxx">
				@error('injury_report_datetime')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>
            
            <div class="mb-4">
				<label for="comment" class="block text-gray-700 text-sm font-bold mb-2">{{__('Zapažanje/komentar')}}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="comment" name="comment" >{{ $accident->comment }}</textarea>
				@error('comment')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>
            <button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{__('Izmeni')}}</button>
			</div>

			
		</form>
    </div>

</x-app-layout>

<script>
    var lang = document.getElementsByTagName('html')[0].getAttribute('lang');
    $.datetimepicker.setLocale(lang);

	$('#injury_datetime').datetimepicker({
		timepicker: true,
		format: 'd.m.Y H:i',
		maxDate: 0,
		dayOfWeekStart: 1,
    	scrollInput: false
    });
    
    $('#injury_report_datetime').datetimepicker({
		timepicker: true,
		format: 'd.m.Y H:i',
		maxDate: 0,
		dayOfWeekStart: 1,
    	scrollInput: false
	});

	

	
</script>