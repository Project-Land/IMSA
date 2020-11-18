<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Vrednovanje zakonskih i drugih zahteva - Kreiranje') }} 
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
            <a class="btn btn-light" href="{{ route('evaluation-of-requirements.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto md:w-4/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('evaluation-of-requirements.store') }}" method="POST" autocomplete="off"  class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="year" class="block text-gray-700 text-sm font-bold mb-2">Nivo sa kojeg zahtev potiče</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="requirement_level" name="requirement_level" required oninvalid="this.setCustomValidity('Izaberite nivo')" oninput="this.setCustomValidity('')">
                       
                            <option value="">Izaberite...</option>
                            <option value="Evropska Unija">Evropska Unija</option>
                            <option value="Država">Država</option>
                            <option value="Grad">Grad</option>
                            <option value="Lokalna samouprava">Lokalna samouprava</option>
                            <option value="Partner">Partner(kupac,dobavljač...)</option>
                            <option value="Ostalo">Ostalo</option>

                       
                    </select>
                </div>
                
                <div class="form-group col-md-6">
                    <label for="responsibility" class="block text-gray-700 text-sm font-bold mb-2">Naziv dokumenta/zakona, ili opis zahteva</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="document_name" id="document_name" required oninvalid="this.setCustomValidity('Popunite polje')" oninput="this.setCustomValidity('')" value="{{old('document_name')}}">
                    @error('document_name')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>
                
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="goal" class="block text-gray-700 text-sm font-bold mb-2">Ocena usaglašenosti</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="compliance" name="compliance" required oninvalid="this.setCustomValidity('Izaberite ocenu usaglašenosti')" oninput="this.setCustomValidity('')">
                            <option value="">Izaberite...</option>
                            <option value="1">Usaglašen</option>
                            <option value="0">Neusaglašen</option>
                    </select>
                    @error('compliance')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="deadline" class="block text-gray-700 text-sm font-bold mb-2">Napomena</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  name="note" id="note" value="{{old('note')}}">
                    @error('note')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>
            </div>

          
            
            <button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">Kreiraj</button>
        </form>
    </div>

</x-app-layout>

<script>

</script>