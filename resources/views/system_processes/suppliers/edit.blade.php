<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Odabrani isporučioci') }}  - {{ __('Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('suppliers.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf
			@method('PUT')
			
            <div class="mb-4">
				<label for="supplier_name" class="block text-gray-700 text-sm font-bold mb-2">Naziv isporučioca:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="supplier_name" name="supplier_name" value="{{ $supplier->supplier_name }}" autofocus>
				@error('supplier_name')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="subject" class="block text-gray-700 text-sm font-bold mb-2">Predmet nabavke:</label>
                <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="subject" name="subject">{{ $supplier->subject }}</textarea>
				@error('subject')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="personal_info" class="block text-gray-700 text-sm font-bold mb-2">Ime:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="personal_info" name="personal_info" value="{{ $supplier->personal_info }}">
				@error('personal_info')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="phone_number" class="block text-gray-700 text-sm font-bold mb-2">Broj telefona:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="phone_number" name="phone_number" value="{{ $supplier->phone_number }}">
				@error('phone_number')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
				<input type="email" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" value="{{ $supplier->email }}">
				@error('email')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="quality" class="block text-gray-700 text-sm font-bold mb-2">Kvalitet:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="quality" id="quality">
					@for($i = 1; $i <= 5; $i++)
						<option value="{{ $i }}" {{ ($supplier->quality == $i) ? "selected" : "" }} >{{ $i }}</option>
					@endfor
				</select>
			</div>

			<div class="mb-4">
				<label for="price" class="block text-gray-700 text-sm font-bold mb-2">Cena:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="price" id="price">
					@for($i = 1; $i <= 5; $i++)
						<option value="{{ $i }}" {{ ($supplier->price == $i) ? "selected" : "" }}>{{ $i }}</option>
					@endfor
				</select>
			</div>

			<div class="mb-4">
				<label for="shippment_deadline" class="block text-gray-700 text-sm font-bold mb-2">Rok isporuke:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="shippment_deadline" id="shippment_deadline">
					@for($i = 1; $i <= 5; $i++)
						<option value="{{ $i }}" {{ ($supplier->shippment_deadline == $i) ? "selected" : "" }} >{{ $i }}</option>
					@endfor
				</select>
			</div>

			<button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">Izmeni</button>
		</form>
    </div>

</x-app-layout>
