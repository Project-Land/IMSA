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

		<form action="{{route('internal-check.update',$internalCheck->id)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="date">Termin provere</label>
                <input type="text" class="form-control" id="date" name="date" value="{{ date('d.m.Y', strtotime( $internalCheck->date)) }}">
                @error('date')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

        <div class="form-group">
            <label for="sector">Područje promene</label>
            <select class="form-control" id="sector" name="sector_id">
            @foreach($sectors as $sector)
            <option value="{{$sector->id}}">{{$sector->name}}</option>
            @endforeach
            </select>
            @error('sector_id')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

            <div class="form-group">
                <label for="leaders">Vođe tima</label>
                <select class="form-control" id="leaders" name="leaders">
                @foreach($teamLeaders as $teamLeader)
                <option value="{{$teamLeader->name}}">{{$teamLeader->name}}</option>
                @endforeach
                </select>
                @error('leaders')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="standard_id">Standard</label>
                <select class="form-control" id="standard_id" name="standard_id">
                <option value="{{$internalCheck->standard_id}}">{{$internalCheck->standard->name}}</option>
                <option value="1">9001</option>
                <option>1005</option>
                </select>
                @error('standard_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
                
            <button type="submit" class="btn btn-primary">Izmeni</button>
        </form>
    </div>

    
    <script>
        jQuery.datetimepicker.setLocale('sr');
   	    $('#date').datetimepicker({
		    format: 'd.m.Y'
	    });

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