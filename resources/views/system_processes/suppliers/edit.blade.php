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
            <div class="form-group">
				<label for="supplier_name">Naziv isporučioca:</label>
				<input type="text" class="form-control" id="supplier_name" name="supplier_name" value="{{ $supplier->supplier_name }}" autofocus>
				@error('supplier_name')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="subject">Predmet nabavke:</label>
                <textarea class="form-control" id="subject" name="subject">{{ $supplier->subject }}</textarea>
				@error('subject')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="personal_info">Ime:</label>
				<input type="text" class="form-control" id="personal_info" name="personal_info" value="{{ $supplier->personal_info }}">
				@error('personal_info')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="phone_number">Broj telefona:</label>
				<input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $supplier->phone_number }}">
				@error('phone_number')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="email">Email:</label>
				<input type="email" class="form-control" id="email" name="email" value="{{ $supplier->email }}">
				@error('email')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="quality">Kvalitet:</label>
				<select class="form-control" name="quality" id="quality">
					@for($i = 1; $i <= 5; $i++)
						<option value="{{ $i }}" {{ ($supplier->quality == $i) ? "selected" : "" }} >{{ $i }}</option>
					@endfor
				</select>
			</div>

			<div class="form-group">
				<label for="price">Cena:</label>
				<select class="form-control" name="price" id="price">
					@for($i = 1; $i <= 5; $i++)
						<option value="{{ $i }}" {{ ($supplier->price == $i) ? "selected" : "" }}>{{ $i }}</option>
					@endfor
				</select>
			</div>

			<div class="form-group">
				<label for="shippment_deadline">Rok isporuke:</label>
				<select class="form-control" name="shippment_deadline" id="shippment_deadline">
					@for($i = 1; $i <= 5; $i++)
						<option value="{{ $i }}" {{ ($supplier->shippment_deadline == $i) ? "selected" : "" }} >{{ $i }}</option>
					@endfor
				</select>
			</div>
			<button type="submit" class="btn btn-success">Izmeni</button>
		</form>
    </div>

</x-app-layout>
