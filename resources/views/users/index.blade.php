<x-app-layout>
    @push('scripts')
        <!-- Datatable -->
        <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ $users->first()->currentTeam->name }} - {{ __('Lista korisničkih naloga') }}
        </h2>
    </x-slot>

    <div class="row mt-1">
        <div class="col">
            @if(Session::has('status'))
                <x-alert :type="Session::get('status')[0]" :message="Session::get('status')[1]"/>
            @endif
        </div>
    </div>

    <a class="inline-flex hover:no-underline items-center px-4 py-2 mb-3 bg-blue-600 border border-transparent rounded font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150"  href="{{ route('users.create') }}"><i class="fas fa-plus mr-2"></i> {{ __('Kreiraj novog korisnika') }}</a>

    <div class="flex flex-col">
		<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
			<div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
				<div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
					<div class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
						<table class="stripe hover min-w-full divide-y divide-gray-200 yajra-datatable">
							<thead>
								<tr>
									<th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
										{{ __('Ime') }}
									</th>
									<th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
										{{ __('Uloga') }}
									</th>
                                    <th class="no-sort px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
										{{ __('Obuke') }}
									</th>
                                    <th class="no-sort px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
										{{ __('Dozvole pristupa') }}
									</th>
									<th class="no-sort px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Akcije') }}</th>
								</tr>
							</thead>
							<tbody class="bg-white divide-y divide-gray-200">
								@foreach($users as $user)
									@if($user->hasTeamRole($user->currentTeam, 'super-admin')) @continue @endif
									<tr id="userRow-{{$user->id}}">
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
											{{ ($user->teamRole($user->currentTeam)->name) === "Owner" ? "Super Admin" : __($user->teamRole($user->currentTeam)->name) }}
										</td>
                                        <td class="px-6 py-2 whitespace-no-wrap text-sm leading-5 text-gray-500">
											<span class="cursor-pointer" onclick="toggleTrainingsModal({{ $user->id }})">{{ __('Lista obuka') }}</span>
										</td>
                                        <td class="px-6 py-2 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            @if(!$user->hasTeamRole($user->currentTeam, 'user'))
                                                <span class="cursor-pointer" onclick="toggleCertsModal('{{ $user->id }}', '{{ $user->certificates }}')">{{ __('Lista dozvola pristupa') }}</span>
                                            @else
                                                /
                                            @endif
                                        </td>
										<td class="px-6 py-2 whitespace-no-wrap text-sm leading-5 font-medium">
											<form class="inline" id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST">
												@method('DELETE')
												@csrf
												<button class="button text-danger pl-2 cursor-pointer" type="button" onclick="confirmDeleteModal({{ $user->id }})">{{ __('Ukloni') }}</button>
											</form>
										</td>
									</tr>

                                    <div class="modal fade" id="trainings-modal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="trainings-modal-{{ $user->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <div class="text-lg">
                                                        {{ __('Lista obuka za korisnika') }}: {{ $user->name }}
                                                    </div>
                                                    <div class="mt-4">
                                                        <div class="mt-1 border border-gray-200 rounded-lg">
                                                            @forelse($user->trainings->unique() as $training)
                                                                <div class="px-4 py-3 border-t border-gray-200 flex flex-wrap justify-between">
                                                                    <div class="w-full sm:w-1/2">
                                                                        <p class="font-bold">{{ $training->name }} ({{ $training->standard->name }})</p>
                                                                        <p class="text-sm">{{ date('d.m.Y', strtotime($training->training_date)) }} {{ __('u') }} {{ date('H:i', strtotime($training->training_date)) }}, {{ $training->place }}</p>
                                                                    </div>
                                                                    <div class="w-full sm:w-1/2">
                                                                        <p class="text-sm italic">{{ __('Dokumenti') }}:</p>
                                                                        @foreach($training->documents->unique() as $doc)
                                                                            <form class="inline" action="{{ route('document.preview') }}" method="POST" target="_blank">
                                                                                @csrf
                                                                                <input type="hidden" name="folder" value="{{ Str::snake(Auth::user()->currentTeam->name).'/'.$doc->doc_category }}">
                                                                                <input type="hidden" name="file_name" value="{{ $doc->file_name }}">
                                                                                <button class="button text-sm text-blue-700 hover:text-blue-500 cursor-pointer text-left" type="submit">{{ $doc->file_name }}</button>
                                                                            </form><br>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @empty
                                                                <div class="px-4 py-3 border-t border-gray-200 flex flex-wrap justify-between">
                                                                    /
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="px-6 py-4 bg-gray-100 text-right">
                                                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Zatvori') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
    </div>

    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="px-6 py-4">
                    <div class="text-lg">{{ __('Brisanje korisnika') }}</div>
                    <div class="mt-4">{{ __('Da li ste sigurni?') }}</div>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Odustani') }}</button>
                    <a class="btn-ok hover:no-underline cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150 ml-2">{{ __('Ukloni') }}</a>
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
        "columnDefs": [{
          "targets": 'no-sort',
          "orderable": false,
        }],
        "pageLength": 25
    });

    function confirmDeleteModal(id){
        $('#confirm-delete-modal').modal();
        $('#confirm-delete-modal').on('click', '.btn-ok', function(e) {
            axios.get('/users/deleteApi/'+id)
            .then(function (response) {
                if(response.data.message){
                    $('#userRow-'+id).remove();
                }else{
                    alert('Error');
                }
            })
            $('#confirm-delete-modal').off();
            $('#confirm-delete-modal').modal('hide');
        });
    }

    function toggleTrainingsModal(id){
        $('#trainings-modal-'+id).modal();
    }

    function toggleCertsModal(id, certs){
        let userCerts = JSON.parse(certs);
        let arrayOfCerts = [];
        $.each(userCerts, function(i, item){
            arrayOfCerts.push(item.pivot.certificate_id.toString())
        })

        let modal = `<div class="modal fade" id="certificates-${ id }" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="text-lg">
                                            {{ __('Upravljaj dozvolama pristupa') }}
                                        </div>
                                        <div class="mt-4">
                                            <div class="mt-1 border border-gray-200 rounded-lg">
                                                @foreach($certificates as $certificate)
                                                    <div class="px-4 py-3 border-t border-gray-200">
                                                        <label class="inline-flex items-center mt-3 cursor-pointer">
                                                            <input type="checkbox" name="certificates[]" value="{{ $certificate->id }}" class="form-checkbox h-5 w-5 text-gray-600" ${ arrayOfCerts.includes('{{ $certificate->id }}')? "checked":"" }><span class="ml-2 text-gray-700">{{ __($certificate->display_name) }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 bg-gray-100 text-right">
                                        <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Odustani') }}</button>
                                        <button type="button" onclick="updateUser(${ id })" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150 ml-2" data-dismiss="modal">{{ __('Sačuvaj') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>`;

            $("body").append(modal);
            $('#certificates-'+id).modal();
    }

    function updateUser(id){
        var selecteditems = [];

        $("#certificates-"+id).find("input:checked").each(function (i, ob) {
            selecteditems.push($(ob).val());
        });

        axios.post('/update-user-certificates/'+id, {
            selecteditems
        })
        .then((response) => {
            //console.log(response)
        })
        .catch((error) => {
            console.log(error)
        });
    }

</script>
