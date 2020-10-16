<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Odobreni isporučioci') }}  - {{ __('Kreiranje') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('suppliers.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto w-50 mt-10 bg-secondary p-10 rounded">

		<form action="{{ route('suppliers.store') }}" method="POST">
			@csrf
			<div class="form-group">
				<label for="supplier_name">Naziv isporučioca:</label>
				<input type="text" class="form-control" id="supplier_name" name="supplier_name" value="{{ old('supplier_name') }}" autofocus>
				@error('supplier_name')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="subject">Predmet nabavke:</label>
                <textarea class="form-control" id="subject" name="subject">{{ old('subject') }}</textarea>
				@error('subject')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="personal_info">Ime:</label>
				<input type="text" class="form-control" id="personal_info" name="personal_info" value="{{ old('personal_info') }}">
				@error('personal_info')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="phone_number">Broj telefona:</label>
				<input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
				@error('phone_number')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="email">Email:</label>
				<input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
				@error('email')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="quality">Kvalitet:</label>
				<select class="form-control" name="quality" id="quality">
					@for($i = 1; $i <= 5; $i++)
						<option value="{{ $i }}">{{ $i }}</option>
					@endfor
				</select>
			</div>

			<div class="form-group">
				<label for="price">Cena:</label>
				<select class="form-control" name="price" id="price">
					@for($i = 1; $i <= 5; $i++)
						<option value="{{ $i }}">{{ $i }}</option>
					@endfor
				</select>
			</div>

			<div class="form-group">
				<label for="shippment_deadline">Rok isporuke:</label>
				<select class="form-control" name="shippment_deadline" id="shippment_deadline">
					@for($i = 1; $i <= 5; $i++)
						<option value="{{ $i }}">{{ $i }}</option>
					@endfor
				</select>
			</div>
            
			<button type="submit" class="btn btn-success">Kreiraj</button>
		</form>
    </div>

</x-app-layout>
