<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{session('standard_name').' - Plan IP - Kreiranje/Izmena'}}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="/plan-ip"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{route('plan-ip.update',$planIp->id)}}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <h2 class="mx-auto text-center">{{'Plan IP - '.$planIp->name}}</h2>
            
            <div class="mb-4">
                <label for="checked_date" class="block text-gray-700 text-sm font-bold mb-2">Termin provere</label>
                <input type="text" class="appearance-none border w-full py-2 px-3 bg-gray-200 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="checked_date" name="checked_date" value="{{ date('d.m.Y', strtotime( $planIp->internalCheck->date)) }}" readonly>
            </div>

            <div class="mb-4">
                <label for="checked_sector" class="block text-gray-700 text-sm font-bold mb-2">Sektor</label>
                <input type="text" class="appearance-none border w-full py-2 px-3 bg-gray-200 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="checked_sector" name="checked_sector" value="{{$planIp->internalCheck->sector->name}}" readonly>
            </div>

            <div class="mb-4">
                <label for="team_for_internal_check" class="block text-gray-700 text-sm font-bold mb-2">Tim za proveru</label>
                <input type="text" class="appearance-none border w-full py-2 px-3 bg-gray-200 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="team_for_internal_check" name="team_for_internal_check" value=" {{$planIp->internalCheck->leaders}}" readonly>
            </div>
        
            <div class="mb-4">
                <label for="check_start" class="block text-gray-700 text-sm font-bold mb-2">Početak provere</label>
                <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="xx.xx.xxxx xx:xx" id="check_start" name="check_start" value="@if($planIp->check_start){{ date('d.m.Y H:i', strtotime($planIp->check_start))  }} @endif" autocomplete="off">
                @error('check_start')
                    <span class="text-red-700 italic text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="check_end" class="block text-gray-700 text-sm font-bold mb-2">Završetak provere</label>
                <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="xx.xx.xxxx xx:xx" id="check_end" name="check_end" value="@if($planIp->check_end) {{ date('d.m.Y H:i', strtotime($planIp->check_end)) }} @endif" autocomplete="off">
                @error('check_end')
                    <span class="text-red-700 italic text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="report_deadline" class="block text-gray-700 text-sm font-bold mb-2">Rok za dostavljanje izveštaja</label>
                <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="xx.xx.xxxx" id="report_deadline" name="report_deadline" value="@if($planIp->report_deadline) {{ date('d.m.Y', strtotime($planIp->report_deadline)) }} @endif" autocomplete="off">
                @error('report_deadline')
                    <span class="text-red-700 italic text-sm">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">Sačuvaj</button>
        </form>
    </div>

    <script>

        jQuery.datetimepicker.setLocale('sr');

        let start_date_first = $('#checked_date').val().split(" ")[0].split(".").reverse().join("/").toString();
        $('#check_start').datetimepicker({
            minDate: start_date_first
        })

        $('#check_start').datetimepicker({
            format: 'd.m.Y H:i',
            dayOfWeekStart: 1,
            scrollInput: false
        });

        $('#check_end').datetimepicker({
            format: 'd.m.Y H:i',
            minDate: start_date_first,
            dayOfWeekStart: 1,
            scrollInput: false
        });

        $('#report_deadline').datetimepicker({
            format: 'd.m.Y',
            timepicker: false,
            minDate: start_date_first,
            dayOfWeekStart: 1,
            scrollInput: false
        });

        $('#check_start').change( () => {
            let start_date = $('#check_start').val().split(" ")[0].split(".").reverse().join("/").toString();
            $('#check_end').datetimepicker({
                minDate: start_date
            })
        })

        $('#check_end').change( () => {
            let start_date = $('#check_end').val().split(" ")[0].split(".").reverse().join("/").toString();
            $('#report_deadline').datetimepicker({
                minDate: start_date
            })
        })

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