<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $users->first()->currentTeam->name }} - {{ __('Lista korisničkih naloga') }}
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
		<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
			<div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
				<div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
					<div class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
						<table class="stripe hover min-w-full divide-y divide-gray-200 yajra-datatable">
							<thead>
								<tr>
									<th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
										Ime
									</th>
									<th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
										Uloga
									</th>
									<th class="px-6 py-3 bg-gray-50">Akcije</th>
								</tr>
							</thead>
							<tbody class="bg-white divide-y divide-gray-200">
								@foreach($users as $user)
									@if($user->teamRole($user->currentTeam)->name === "Owner") @continue @endif
									<tr>
										<td class="px-6 py-2 whitespace-no-wrap">
											<div class="flex items-center">
												<div class="flex-shrink-0 h-10 w-10">
													<img class="h-10 w-10 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
												</div>
												<div class="ml-4">
													<div class="text-sm leading-5 font-medium text-gray-900">
														{{ $user->name }}
													</div>
													<div class="text-sm leading-5 text-gray-500">
														{{ $user->username }}
													</div>
												</div>
											</div>
										</td>
										<td class="px-6 py-2 whitespace-no-wrap text-sm leading-5 text-gray-500">
											{{ ($user->teamRole($user->currentTeam)->name) === "Owner" ? "Super Admin" : $user->teamRole($user->currentTeam)->name }}
										</td>
										<td class="px-6 py-2 whitespace-no-wrap text-sm leading-5 font-medium">
											<form class="inline " action="{{ route('users.destroy', $user->id) }}" method="POST">
												@method('DELETE')
												@csrf
												<button class="button text-danger pl-2 cursor-pointer" type="submit" onclick="return confirm('Da li ste sigurni?');">Ukloni</button>
											</form>
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
        "columnDefs": [{
          "targets": 'no-sort',
          "orderable": false,
        }],
    }); 

</script>