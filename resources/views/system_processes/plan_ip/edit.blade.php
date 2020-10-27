<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kreiraj novi Plan IP za standard ') }} {{session('standard_name')}}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="/plan-ip"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto w-50 mt-10 bg-secondary p-10 rounded">

		<form action="{{route('plan-ip.update',$planIp->id)}}" method="POST">
            @csrf
            @method('PUT')

        <h2 class="mx-auto text-center">{{'Plan IP - '.$planIp->name}}</h2>
        <div class="form-group">
            <label for="checked_date">Termin provere</label>
            <input type="text" class="form-control" id="checked_date" name="checked_date" value="{{ date('d.m.Y', strtotime( $planIp->internalCheck->date)) }}" readonly>
        </div>

        <div class="form-group">
            <label for="checked_sector">Sektor</label>
            <input type="text" class="form-control" id="checked_sector" placeholder="" name="checked_sector" value="{{$planIp->internalCheck->sector->name}}" readonly>
        </div>

        <div class="form-group">
            <label for="team_for_internal_check">Tim za proveru</label>
            <input type="text" class="form-control" id="team_for_internal_check" name="team_for_internal_check" value="{{$planIp->internalCheck->leaders}}" readonly>
        </div>
       
        <div class="form-group">
            <label for="check_start">Početak provere</label>
            <input type="text" class="form-control" id="check_start" name="check_start" value="{{ date('d.m.Y H:i', strtotime($planIp->check_start)) }}">
        </div>

        <div class="form-group">
            <label for="check_end">Završetak provere</label>
            <input type="text" class="form-control" id="check_end" name="check_end" value="{{ date('d.m.Y H:i', strtotime($planIp->check_end)) }}">
        </div>

        <div class="form-group">
            <label for="report_deadline">Rok za dostavljanje izveštaja</label>
            <input type="text" class="form-control" id="report_deadline" name="report_deadline" value="{{ date('d.m.Y', strtotime($planIp->report_deadline)) }}">
        </div>

        
            
        <button type="submit" class="btn btn-primary">Izmeni</button>
        </form>
    </div>

    <script>
        jQuery.datetimepicker.setLocale('sr');
        $('#check_start').datetimepicker({format: 'd.m.Y H:i'});
        $('#check_end').datetimepicker({format: 'd.m.Y H:i'});
        $('#report_deadline').datetimepicker({format: 'd.m.Y'});

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