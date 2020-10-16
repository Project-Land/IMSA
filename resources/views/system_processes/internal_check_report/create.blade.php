<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kreiraj novi Izveštaj sa interne provere ') }} {{session('standard_name')}}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="/internal-check"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto w-50 mt-10 bg-secondary p-10 rounded">

		<form action="{{route('internal-check-report.store')}}" method="POST">
            @csrf
        

        <div class="form-group">
            <label for="checked_sector">Proveravano područje</label>
            <input type="text" class="form-control" id="checked_sector" placeholder="" name="checked_sector" value="{{$internalCheck->sector}}">
        </div>

        <div class="form-group">
            <label for="standard">Standard</label>
            <input type="text" class="form-control" id="standard" placeholder="" name="standard" value="{{$internalCheck->standard->name}}">
        </div>

        <div class="form-group">
            <label for="team_for_internal_check">Tim za proveru</label>
            <input type="text" class="form-control" id="team_for_internal_check" placeholder="" name="team_for_internal_check" value="{{$internalCheck->leaders}}">
        </div>

    
       
        <div class="form-group">
            <label for="check_start">Početak provere</label>
            <input type="text" class="form-control" id="check_start" placeholder="" name="check_start" value="{{$internalCheck->planIp->check_start}}">
        </div>

        <div class="form-group">
            <label for="check_end">Završetak provere</label>
            <input type="text" class="form-control" id="check_end" placeholder="" name="check_end" value="{{$internalCheck->planIp->check_end}}">
        </div>

        <div class="form-group">
            <label for="specification">Specifikacija dokumenata</label>
            <input type="text" class="form-control" id="specification" placeholder="" name="specification">
        </div>

        <div class="form-group">
            <label for="inconsistencies">Neusaglašenosti</label>
            <select class="form-control" id="inconsistencies" name="inconsistencies" >
            <option value="0">ne</option>
            <option value="1">da</option>
            </select>
        </div>
        <div class="form-group">
            <label for="recommendations">Neusaglašenosti</label>
            <select class="form-control" id="recommendations" name="recommendations">
            <option value="0">ne</option>
            <option value="1">da</option>
            </select>
        </div>

        
            
        <button type="submit" class="btn btn-primary">Izmeni</button>
        </form>
    </div>

    <script>


        
    </script>

</x-app-layout>