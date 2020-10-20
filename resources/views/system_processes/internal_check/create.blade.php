<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kreiraj novi dokument za Standard') }} {{session('standard_name')}}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="/internal-check"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto w-50 mt-10 bg-secondary p-10 rounded">

		<form action="{{route('internal-check.store')}}" method="POST">
			@csrf
        <div class="form-group">
            <label for="date">Termin provere</label>
            <input type="text" class="form-control" id="date" placeholder="" name="date">
        </div>

        <div class="form-group">
            <label for="sector">Područje promene</label>
            <select class="form-control" id="sector" name="sector">
            <option value="prodaja">Prodaja</option>
            <option>Marketing</option>
            </select>
        </div>

        <div class="form-group">
            <label for="leaders">Vođe tima</label>
            <select class="form-control" id="leaders" name="leaders">
            <option value="nikola">Nikola</option>
            <option>Milos</option>
            </select>
        </div>

        <div class="form-group">
            <label for="standard_id">Standard</label>
            <select class="form-control" id="standard_id" name="standard_id">
            <option value="1">9001</option>
            <option>1005</option>
            </select>
        </div>
            
        <button type="submit" class="btn btn-primary">Kreiraj</button>
        </form>
    </div>

    <script>
   	$('#date').datetimepicker();

	$('#status').change( () => {
		if($('#status').val() == 1){
			$('#final_num_field').css('display', '');
			$('#rating_field').css('display', '');
		}
		else{
			$('#final_num_field').css('display', 'none');
			$('#rating_field').css('display', 'none');
			$('#final_num_of_employees').val('');
			$('#rating').val('');
		}
	})
</script>

</x-app-layout>
