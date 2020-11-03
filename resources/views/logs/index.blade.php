<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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
        </div>
    </div>

    <div class="flex flex-col">

		<div class="-my-2 overflow-x-auto md:overflow-x-visible sm:-mx-6 lg:-mx-8">

			<div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                <div class="pl-4 py-2 bg-white shadow rounded">
                    <p><b>Poslednja promena:</b> <i>{{ date('d.m.Y H:i:s', strtotime($data['lastModified'])) }}</i></p>
                    <p><b>Veličina fajla:</b> {{ round($data['size'] / 1024) }} KB</p>
                </div>

				<div class="shadow border-b border-gray-200 sm:rounded-lg pt-4">

                    <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">

                        <table id="example" class="stripe hover min-w-full divide-y divide-gray-200 yajra-datatable">
                            <thead>
                                <tr>
                                    <th class="text-center px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Tip
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Poruka
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Korisnik
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Datum
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($logs as $key => $log)
                                <tr>
                                    <td class="py-4 px-6 whitespace-no-wrap text-sm leading-5 text-center {{ $log['status'] == "WARNING" ? "text-red-700":"text-blue-700" }}">
                                        {{ $log['status'] }}
                                    </td>
                                    <td class="py-4 px-6 whitespace-no-wrap text-sm leading-5">
                                        {{ $log['message'] }}
                                        @if(!empty($log['error']))
                                        <br> <p style="cursor: pointer; color: blue;" id="error-{{ $key }}" onclick="showError({{ $key }})">Greška</p>
                                        <div class="modal" id="showError-{{ $key }}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content rounded-0">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title font-weight-bold">Greška</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col overflow-auto text-wrap">{{ $log['error'] }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Zatvori</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ Str::of($log['user'])->split('/[,]+/')[0] }}<br>
                                        {{ Str::of($log['user'])->split('/[,]+/')[1] }}
                                    </td>
                                    <td class="py-4 px-6 whitespace-no-wrap text-sm leading-5 text-gray-500">
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
            "info": "Prikaz strane _PAGE_ od _PAGES_",
            "infoEmpty": "Nema podataka",
            "zeroRecords": "Nema podataka",
            "infoFiltered": "(od _MAX_ ukupno rezultata)",
            "lengthMenu": "Prikaži _MENU_ redova po stranici",
            "search": "Pretraga",
            "paginate": {
                "next": "Sledeća",
                "previous": "Prethodna",
                "first": "Prva",
                "last": "Poslednja"
            }
        },
        "order": [[ 3, "desc" ]],
        "columnDefs": [{
          "targets": 'no-sort',
          "orderable": false,
        }],
    }); 

    function showError(id)
    {
        $('#showError-'+id).modal();
    }
</script>

<style>
     <style>
		
		/*Overrides for Tailwind CSS */
		
		/*Form fields*/
		.dataTables_wrapper select,
		.dataTables_wrapper .dataTables_filter input {
			color: #4a5568; 			/*text-gray-700*/
			padding-left: 1rem; 		/*pl-4*/
			padding-right: 1rem; 		/*pl-4*/
			padding-top: .5rem; 		/*pl-2*/
			padding-bottom: .5rem; 		/*pl-2*/
			line-height: 1.25; 			/*leading-tight*/
			border-width: 2px; 			/*border-2*/
			border-radius: .25rem; 		
			border-color: #edf2f7; 		/*border-gray-200*/
			background-color: #edf2f7; 	/*bg-gray-200*/
		}

		/*Row Hover*/
		table.dataTable.hover tbody tr:hover, table.dataTable.display tbody tr:hover {
			background-color: #ebf4ff;	/*bg-indigo-100*/
		}
		
		/*Pagination Buttons*/
		.dataTables_wrapper .dataTables_paginate .paginate_button		{
			font-weight: 700;				/*font-bold*/
			border-radius: .25rem;			/*rounded*/
			border: 1px solid transparent;	/*border border-transparent*/
		}
		
		/*Pagination Buttons - Current selected */
		.dataTables_wrapper .dataTables_paginate .paginate_button.current	{
			color: #fff !important;				/*text-white*/
			box-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px 0 rgba(0,0,0,.06); 	/*shadow*/
			font-weight: 700;					/*font-bold*/
			border-radius: .25rem;				/*rounded*/
			background: #667eea !important;		/*bg-indigo-500*/
			border: 1px solid transparent;		/*border border-transparent*/
		}

		/*Pagination Buttons - Hover */
		.dataTables_wrapper .dataTables_paginate .paginate_button:hover		{
			color: #fff !important;				/*text-white*/
			box-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px 0 rgba(0,0,0,.06);	 /*shadow*/
			font-weight: 700;					/*font-bold*/
			border-radius: .25rem;				/*rounded*/
			background: #667eea !important;		/*bg-indigo-500*/
			border: 1px solid transparent;		/*border border-transparent*/
		}
		
		/*Add padding to bottom border */
		table.dataTable.no-footer {
			border-bottom: 1px solid #e2e8f0;	/*border-b-1 border-gray-300*/
			margin-top: 0.75em;
			margin-bottom: 0.75em;
		}
		
		/*Change colour of responsive icon*/
		table.dataTable.dtr-inline.collapsed>tbody>tr>td:first-child:before, table.dataTable.dtr-inline.collapsed>tbody>tr>th:first-child:before {
			background-color: #667eea !important; /*bg-indigo-500*/
		}
		
      </style>
</style>