<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista firmi') }}
        </h2>
    </x-slot>

    <div class="row mt-1">
        <div class="col">
            @if(Session::has('status'))
                <x-alert :type="Session::get('status')[0]" :message="__(Session::get('status')[1])"/>
            @endif
        </div>
    </div>

    <div class="flex flex-col">
		<div class="-my-2 overflow-x-auto md:overflow-x-visible sm:-mx-6 lg:-mx-8">
			<div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
				<div class="shadow border-b border-gray-200 sm:rounded-lg">
					<table class="min-w-full divide-y divide-gray-200">
						<thead>
							<tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Firma') }}
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Standardi') }}
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Sadržaj') }}
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Br. korisnika') }}
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Logovi') }}
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Akcije') }}
                                </th>
							</tr>
						</thead>
						<tbody class="bg-white divide-y divide-gray-200">
							@foreach($teams as $team)
							<tr>
								<td class="px-6 py-4 whitespace-no-wrap">
									{{ $team->name }}
								</td>
								<td class="py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                    <div class="relative inline-block text-left">
                                        <div class="inline-block">
                                            <x-jet-dropdown width="48">

                                                <x-slot name="trigger">
                                                    <div>
                                                        <span class="shadow-sm">
                                                            <button type="button" class="inline-flex justify-center w-full px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150">
                                                                {{ __('Standardi') }}
                                                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </x-slot>

                                                <x-slot name="content">
                                                    <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg">
                                                        <div class="rounded-md bg-white shadow-xs">
                                                            @foreach($team->standards as $standard)
                                                                <div class="py-1 flex justify-between hover:bg-gray-200">
                                                                    <p class="text-center px-4 py-1 text-sm m-1 leading-5 text-gray-700 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">{{ $standard->name }}</p>
                                                                    <form id="delete-form-{{ $standard->id }}" action="{{ route('standards.destroy', $standard->id) }}" method="POST">
                                                                        @method('DELETE')
                                                                        @csrf
                                                                        <input type="hidden" name="team_id" value="{{ $team->id }}">
                                                                        <button class="text-center px-4 py-1 m-1 text-sm leading-5 text-red-700 hover:text-red-900 focus:outline-none focus:bg-red-100 focus:text-red-900 cursor-pointer" type="button" onclick="confirmDeleteModal({{ $standard->id }})"><i class="fas fa-trash"></i></button>
                                                                    </form>
                                                                </div>
                                                                <div class="border-t border-gray-100"></div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </x-slot>

                                            </x-jet-dropdown>
                                        </div>
                                        <a class="inline-block px-2" href="{{ route('standards.create-new', $team->id) }}"><i class="fas fa-plus"></i></a>
                                    </div>
                                </td>
                                <td class="py-4 px-6 whitespace-no-wrap text-sm leading-5 text-gray-500">
									<a href="/change-current-team/{{ $team->id }}">{{ __('Pregled') }}</a>
                                </td>
                                <td class="py-4 px-6 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                    {{ __('Trenutno') }}: {{ $team->users->count() - 2 }}<br>
                                    <span class="cursor-pointer text-blue-500 hover:text-blue-700" id="teamStats" onclick="showStats({{ $team->id }})">{{ __('Statistika') }}</span>
                                </td>
                                <td class="py-4 px-6 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                    <a href="{{ route('logs.show', $team->name) }}">Log</a>
								</td>
								<td class="py-4 px-6 whitespace-no-wrap text-sm leading-5 font-medium">
                                    <a href="/teams/{{ $team->id }}">{{ __('Izmeni') }}</a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
    </div>

    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="px-6 py-4">
                    <div class="text-lg">{{ __('Brisanje standarda') }}</div>
                    <div class="mt-4">{{ __('Da li ste sigurni?') }}</div>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Odustani') }}</button>
                    <a class="btn-ok hover:no-underline cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150 ml-2">{{ __('Obriši') }}</a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<script>

    function showStats(id)
    {
        axios.get('/show-team-stats/'+id)
            .then((response) => {
                let modal = `<div class="modal fade" id="showTeamStats-${ id }" tabindex="-1" role="dialog">
                                <div class="modal-dialog bg-white modal-lg rounded-lg overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="document">
                                    <div class="modal-content">
                                        <div class="flex justify-between mb-2">
                                            <h5 class="px-3 py-4 font-bold text-center">{{ __('Broj korisnika po datumima') }}: ${response.data[0].team.name}</h5>
                                            <button type="button" class="close px-4 py-1" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">`;

                                        $.each(response.data, function (i, item){
                                            modal +=`
                                            <div class="row mt-1 text-sm">
                                                <div class="col-sm-5 border-bottom mt-2"><p><b>{{ __('Datum') }}:</b> <i>${new Date(item.check_date).toLocaleString('sr-SR', { timeZone: 'CET' })}</i></p></div>
                                                <div class="col-sm-7 border-bottom mt-2"><p><b>{{ __('Broj korisnika') }}</b>: ${item.total - 2}</p></div>
                                            </div>`
                                        })

                                        modal +=`</div>
                                        <div class="px-6 py-4 bg-gray-100 text-right">
                                            <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Zatvori') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                $("body").append(modal);
                $('#showTeamStats-'+id).modal();
            })
            .catch((error) => {
                console.log(error)
            })
    }

    function confirmDeleteModal($id){
        let id = $id;
        $('#confirm-delete-modal').modal();
        $('#confirm-delete-modal').on('click', '.btn-ok', function(e) {
            let form = $('#delete-form-'+id);
            form.submit();
        });
    }

</script>
