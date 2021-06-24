<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Zadovoljstvo korisnika')}} - {{__('Izmena ankete') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col-sm-2">
        	<a class="btn btn-light" href="{{ route('customer-satisfaction.index') }}"><i class="fas fa-arrow-left"></i> {{__('Nazad')}}</a>
     	</div>
	</div>


	<div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">
		<form action="{{ route('customer-satisfaction.update', $cs->id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf
            @method('PUT')

            <div class="mb-4">
                <label for="customer" class="block text-gray-700 text-sm font-bold mb-2">{{__('Klijent')}}:</label>
                <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="customer" name="customer" value="{{ $cs->customer }}" autofocus required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')">
                @error('customer')
                    <span class="text-red-700 italic text-sm">{{ $message }}</span>
                @enderror
            </div>

            @foreach($poll as $p)
                <div class="mb-4">
                    <label for="{{ $p->column_name }}" class="block text-gray-700 text-sm font-bold mb-2">{{ $p->name}}:</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="{{ $p->column_name }}" name="{{ $p->column_name }}">
						@for($i=1; $i<=5; $i++)
                            <option value="{{ $i }}" {{ $cs->{$p->column_name} == $i ? "selected":"/" }}>{{ $i }}</option>
                        @endfor
					</select>
                    @error('{{ $p->column_name }}')
                        <span class="text-red-700 italic text-sm">{{ $message }}</span>
                    @enderror
                </div>
            @endforeach

            <div class="mb-4">
                <label for="date" class="block text-gray-700 text-sm font-bold mb-2">{{__('Datum')}}:</label>
                <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="date" name="date" value="{{ $cs->date ? date('d.m.Y', strtotime($cs->date)) : $cs->created_at->format('d.m.Y') }}" required oninvalid="this.setCustomValidity('{{__("Izaberite datum")}}')" oninput="this.setCustomValidity('')" onchange="this.setCustomValidity('')" placeholder="xx.xx.xxxx">
                @error('date')
                    <span class="text-red-700 italic text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="comment" class="block text-gray-700 text-sm font-bold mb-2">{{__('Napomena')}}:</label>
                <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="comment" name="comment">{{ $cs->comment }}</textarea>
                @error('comment')
                    <span class="text-red-700 italic text-sm">{{ $message }}</span>
                @enderror
            </div>

			<button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{__('Izmeni')}}</button>
		</form>
	</div>

    <script>
        let lang = document.getElementsByTagName('html')[0].getAttribute('lang');
        $.datetimepicker.setLocale(lang);

        $('#date').datetimepicker({
            timepicker: false,
            format: 'd.m.Y',
            dayOfWeekStart: 1,
            scrollInput: false
        });
    </script>

</x-app-layout>
