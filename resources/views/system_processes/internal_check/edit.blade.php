<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{session('standard_name').' - Godišnji plan internih provera - Izmena'}}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="/internal-check"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{route('internal-check.update',$internalCheck->id)}}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="date">Termin provere</label>
                <input type="text" class="form-control" id="date" name="date" value="{{ date('d.m.Y', strtotime( $internalCheck->date)) }}">
                @error('date')
                <span class="text-red-700 italic text-sm">{{ $message }}</span>
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
            <span class="text-red-700 italic text-sm">{{ $message }}</span>
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
                <span class="text-red-700 italic text-sm">{{ $message }}</span>
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
                <span class="text-red-700 italic text-sm">{{ $message }}</span>
                @enderror
            </div>
                
            <button type="submit" class="btn btn-primary">Izmeni</button>
        </form>
    </div>

    
    <script>
        jQuery.datetimepicker.setLocale('sr');
   	    $('#date').datetimepicker({
            timepicker: false,
		    format: 'd.m.Y',
            dayOfWeekStart: 1,
            minDate: 0
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
