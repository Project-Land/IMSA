<x-app-layout>
    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Plan IP') }} - {{ __('Kreiranje / Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('internal-check.index') }}"><i class="fas fa-arrow-left"></i> {{ __('Nazad') }}</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('plan-ip.update', $planIp->id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <h2 class="mx-auto text-center">{{ __('Plan IP') }}  - {{ $planIp->name }}</h2>

            <div class="mb-4">
                <label for="checked_date" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Termin provere') }}</label>
                <input type="text" class="appearance-none border w-full py-2 px-3 bg-gray-200 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="checked_date" name="checked_date" value="{{ date('d.m.Y', strtotime( $planIp->internalCheck->date)) }}" readonly>
            </div>

            <div class="mb-4">
                <label for="checked_sector" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Sektor') }}</label>
                <input type="text" class="appearance-none border w-full py-2 px-3 bg-gray-200 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="checked_sector" name="checked_sector" value="@foreach(collect($planIp->internalCheck->sectors) as $sector_id){{\App\Models\Sector::find($sector_id)->name.', '}} @endforeach" readonly>
            </div>

            <div class="mb-4">
                <label for="team_for_internal_check" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Tim za proveru') }}</label>
                <input type="text" class="appearance-none border w-full py-2 px-3 bg-gray-200 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="team_for_internal_check" name="team_for_internal_check" value="{{$planIp->internalCheck->leaders}}" readonly>
            </div>

            <div class="mb-4">
                <label for="check_start" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Početak provere') }}</label>
                <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="xx.xx.xxxx xx:xx" id="check_start" name="check_start" value="@if($planIp->check_start){{ date('d.m.Y H:i', strtotime($planIp->check_start))  }} @endif" autocomplete="off" required oninvalid="this.setCustomValidity('{{ __("Izaberite termin") }}')" oninput="this.setCustomValidity('')" onchange="this.setCustomValidity('')">
                @error('check_start')
                    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="check_end" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Završetak provere') }}</label>
                <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="xx.xx.xxxx xx:xx" id="check_end" name="check_end" value="@if($planIp->check_end) {{ date('d.m.Y H:i', strtotime($planIp->check_end)) }} @endif" autocomplete="off" required oninvalid="this.setCustomValidity('{{ __("Izaberite termin") }}')" oninput="this.setCustomValidity('')" onchange="this.setCustomValidity('')">
                @error('check_end')
                    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="report_deadline" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Rok za dostavljanje izveštaja') }}</label>
                <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="xx.xx.xxxx" id="report_deadline" name="report_deadline" value="@if($planIp->report_deadline) {{ date('d.m.Y', strtotime($planIp->report_deadline)) }} @endif" autocomplete="off" required oninvalid="this.setCustomValidity('{{ __("Izaberite termin") }}')" oninput="this.setCustomValidity('')" onchange="this.setCustomValidity('')">
                @error('report_deadline')
                    <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="users" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Korisnici') }}</label>
                <select class="users block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="check_users" name="check_users[]" multiple required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @if(collect($planIp->check_users)->contains($user->id)) {{ 'selected' }} @endif>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('users')
                    <span class="text-red-700 italic text-sm">{{ __($message) }}</span><br>
                @enderror
            </div>

            <button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{ __('Sačuvaj') }}</button>
        </form>
    </div>

    <style>
        .select2-results {
            font-size: 0.875rem;
        }
        .select2-container--default .select2-selection--multiple {
            border-radius: 0;
            padding-top: 5px;
            padding-bottom: 10px;
            border: 1px solid #dee2e6 !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: 1px solid #dee2e6 !important;
        }
        .select2-selection__choice {
            font-size: 0.875rem;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice{
            border-radius: 1px;
        }
    </style>

    <script>

        var lang = document.getElementsByTagName('html')[0].getAttribute('lang');
        jQuery.datetimepicker.setLocale(lang);

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

        $('.users').select2();

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
