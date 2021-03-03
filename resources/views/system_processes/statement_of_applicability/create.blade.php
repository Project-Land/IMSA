<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Izjava o primenjivosti')}} - {{__('Kreiranje') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col-sm-2">
        	<a class="btn btn-light" href="{{ route('statement-of-applicability.index') }}"><i class="fas fa-arrow-left"></i> {{__('Nazad')}}</a>
     	</div>
	</div>

	<div class="mx-auto md:w-full mt-1 md:p-10 sm:p-2 rounded" x-data="{ @foreach($groups as $g) open{{ $g->id }}:true, @endforeach }">
		<form action="{{ route('statement-of-applicability.store', \Auth::user()->current_team_id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf

            @foreach($groups as $group)
                <div class="flex flex-grow">
                    <p class="font-bold cursor-pointer" @click="open{{ $group->id }} = ! open{{ $group->id }}">{{ $group->name }} <i class="ml-2 fas" :class="{'fa-chevron-up': open{{ $group->id }}, 'fa-chevron-down': ! open{{ $group->id }} }"></i></p>
                </div>
                @foreach($fields as $field)
                    @if($group->id == $field->soaField->soa_field_group_id)
                        <div class="flex flex-wrap border-b-2 py-2 my-2" :class="{'': open{{ $group->id }}, 'hidden': ! open{{ $group->id }} }">
                            <div class="w-full sm:w-1/5">
                                <label class="block text-gray-700 text-sm font-bold mb-2">{{__('Naziv kontrole')}}:</label>
                                <p class="text-xs sm:text-sm">{{ $field->soaField->name }}</p>
                            </div>
                            <div class="w-full sm:w-1/5">
                                <label class="block text-gray-700 text-sm font-bold mb-2">{{__('Opis kontrole')}}:</label>
                                <p class="text-xs sm:text-sm">{{ $field->soaField->description }}</p>
                            </div>
                            <div class="w-full sm:w-1/5">
                                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">{{__('Status kontrole')}}:</label>
                                <select class="text-xs sm:text-sm mr-2 block border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="{{ $field->id }}[status]">
                                    <option value="#">{{ __('Izaberi') }}...</option>
                                    <option value="Prihvaćeno">{{ __('Prihvaćeno') }}</option>
                                    <option value="Neprihvaćeno">{{ __('Neprihvaćeno') }}</option>
                                    <option value="Nije primenljivo">{{ __('Nije primenljivo') }}</option>
                                </select>
                                @error('status')
                                    <span class="text-red-700 italic text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="w-full sm:w-1/5">
                                <label for="comment" class="block text-gray-700 text-sm font-bold mb-2">{{__('Komentar')}}:</label>
                                <textarea class="text-xs sm:text-sm appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="comment" name="{{ $field->id }}[comment]" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')"></textarea>
                                @error('comment')
                                    <span class="text-red-700 italic text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach

			<button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{__('Kreiraj')}}</button>
		</form>
	</div>

</x-app-layout>
