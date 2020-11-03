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

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{route('plan-ip.update',$planIp->id)}}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
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
            <input type="text" class="form-control" id="team_for_internal_check" name="team_for_internal_check" value=" {{$planIp->internalCheck->leaders}}" readonly>
        </div>
       
        <div class="form-group">
            <label for="check_start">Početak provere</label>
            <input type="text" class="form-control" id="check_start" name="check_start" value="@if($planIp->check_start){{ date('d.m.Y H:i', strtotime($planIp->check_start))  }} @endif">
        </div>

        <div class="form-group">
            <label for="check_end">Završetak provere</label>
            <input type="text" class="form-control" id="check_end" name="check_end" value="@if($planIp->check_end) {{ date('d.m.Y H:i', strtotime($planIp->check_end)) }} @endif">
        </div>

        <div class="form-group">
            <label for="report_deadline">Rok za dostavljanje izveštaja</label>
            <input type="text" class="form-control" id="report_deadline" name="report_deadline" value="@if($planIp->report_deadline) {{ date('d.m.Y', strtotime($planIp->report_deadline)) }} @endif">
        </div>

        
            
        <button type="submit" class="btn btn-primary">Izmeni</button>
        </form>
    </div>

    <script>

        jQuery.datetimepicker.setLocale('sr');
        $('#check_start').datetimepicker({
            format: 'd.m.Y H:i',
            minDate: 0,
            dayOfWeekStart: 1,
        });

        $('#check_end').datetimepicker({
            format: 'd.m.Y H:i',
            minDate: 0,
            dayOfWeekStart: 1,
        });

        $('#report_deadline').datetimepicker({
            format: 'd.m.Y',
            minDate: 0,
            dayOfWeekStart: 1,
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