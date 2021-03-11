<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ __('Logovi') }}
        </h2>
    </x-slot>

    <div class="row mt-1">
        <div class="col">
            @if(Session::has('status'))
                <div class="alert alert-info alert-dismissible fade show">
                    {{ Session::get('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(Session::has('warning'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ Session::get('warning') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="flex flex-col">

		<div class="-my-2 overflow-x-auto md:overflow-x-visible sm:-mx-6 lg:-mx-8">

			<div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                <div class="w-3/6 md:w-full pl-4 pt-3 pb-1 bg-white shadow rounded" >
                    <p class="text-xs md:text-base"><b>{{ __('Poslednja promena') }}:</b> <i>{{ date('d.m.Y H:i:s', strtotime($data['lastModified'])) }}</i></p>
                    <p class="text-xs md:text-base"><b>{{ __('Veličina fajla') }}:</b> {{ round($data['size'] / 1024) }} KB</p>
                </div>

				<div class="shadow border-b border-gray-200 sm:rounded-lg pt-4">

                    <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">

                        <table id="example" class="stripe hover min-w-full divide-y divide-gray-200 yajra-datatable">
                            <thead>
                                <tr>
                                    <th class="text-center px-6 py-3 bg-gray-50 text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Tip') }}
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Poruka') }}
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Korisnik') }}
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Datum') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($logs as $key => $log)
                                <tr>
                                    <td class="py-2 px-6 whitespace-no-wrap text-sm leading-5 text-center {{ $log['status'] == "WARNING" ? "text-red-700":"text-blue-700" }}">
                                        {{ $log['status'] }}
                                    </td>
                                    <td class="py-2 px-6 whitespace-no-wrap text-sm leading-5">
                                        {{ $log['message'] }}
                                        @if(!empty($log['error']) && \Auth::user()->hasTeamRole(\Auth::user()->currentTeam, 'super-admin'))
                                            <br> <p style="cursor: pointer; color: blue;" id="error-{{ $key }}" onclick="showError({{ $key }})">{{ __('Greška') }}</p>
                                            <div class="modal fade" id="showError-{{ $key }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content rounded-0">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title font-weight-bold">{{ __('Greška') }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col overflow-auto text-wrap">{{ $log['error'] }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="px-6 py-4 bg-gray-100 text-right">
                                                            <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Zatvori') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-2 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ Str::of($log['user'])->split('/[,]+/')[0] }}<br>
                                        {{ Str::of($log['user'])->split('/[,]+/')[1] }}
                                    </td>
                                    <td class="py-2 px-6 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ Str::of($log['time'])->split('/[\s]+/')[0] }} u {{ Str::of($log['time'])->split('/[\s]+/')[1] }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
				</div>
			</div>
		</div>
	</div>

</x-app-layout>

<script>

    $('.yajra-datatable').DataTable({
        "language": {
            "info": "{{__('Prikaz strane')}} _PAGE_ {{__('od')}} _PAGES_",
            "infoEmpty": "{{__('Nema podataka')}}",
            "zeroRecords": "{{__('Nema podataka')}}",
            "infoFiltered": "({{__('od')}} _MAX_ {{__('ukupno rezultata')}})",
            "lengthMenu": "{{__('Prikaži')}} _MENU_ {{__('redova po stranici')}}",
            "search": "{{__('Pretraga')}}",
            "paginate": {
                "next": "{{__('Sledeća')}}",
                "previous": "{{__('Prethodna')}}",
                "first": "{{__('Prva')}}",
                "last": "{{__('Poslednja')}}"
            }
        },
        "order": [[ 3, "desc" ]],
        "columnDefs": [{
          "targets": 'no-sort',
          "orderable": false,
        }],
        "pageLength": 25
    });

    function showError(id)
    {
        $('#showError-'+id).modal();
    }
</script>
