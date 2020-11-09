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
        </div>
    </div>

    <div class="flex flex-col">
		<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
			<div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
				<div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
					<table class="min-w-full divide-y divide-gray-200">
						<thead>
							<tr>
							<th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
								Ime
							</th>
							<th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
								Uloga
							</th>
							<th class="px-6 py-3 bg-gray-50"></th>
							</tr>
						</thead>
						<tbody class="bg-white divide-y divide-gray-200">
							@foreach($users as $user)
							<tr>
								<td class="px-6 py-4 whitespace-no-wrap">
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
								<td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
									{{ ($user->teamRole($user->currentTeam)->name) === "Owner" ? "Super Admin" : $user->teamRole($user->currentTeam)->name }}
								</td>
								<td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
									<!-- <a href="#" class="text-indigo-600 hover:text-indigo-900">Izveštaj</a> -->
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

</x-app-layout>
