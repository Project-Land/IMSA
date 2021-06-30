<x-app-layout>
    @push('scripts')
    <!-- Datatable -->
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.25/sorting/date-de.js"></script>
    @endpush

    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
                {{ Session::get('standard_name') }} - {{ __('Reklamacije') }}
            </h2>
            @include('includes.video')
        </div>
    </x-slot>

    <div class="row mt-1">
        <div class="col">
            @if(Session::has('status'))
            <x-alert :type="Session::get('status')[0]" :message="Session::get('status')[1]" />
            @endif
        </div>
    </div>

    <div class="row mt-3">

        <div class="col">

            <div class="card">
                <div class="card-header">
                    @can('create', App\Models\Complaint::class)
                    <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3"
                        href="{{ route('complaints.create') }}"><i class="fas fa-plus"></i>
                        {{__('Kreiraj reklamaciju')}}</a>
                    @endcan
                    <a class="inline-block float-right text-xs md:text-base bg-green-500 hover:bg-green-700 text-white hover:no-underline rounded py-2 px-3"
                        href="{{ route('complaints.export') }}"><i class="fas fa-file-export"></i> {{ __('Excel') }}</a>
                </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>{{__('Oznaka')}}</th>
                                    <th>{{__('Datum podnošenja')}}</th>
                                    {{-- <th>{{__('Opis')}}</th> --}}
                                    <th>{{__('Dokumenta')}}</th>
                                    <th>{{__('Proces na koji se reklamacija odnosi')}}</th>
                                    <th>{{__('Opravdana / prihvaćena')}}</th>
                                    <th>{{__('Rok za realizaciju')}}</th>
                                    <th>{{__('Lice odgovorno za rešavanje')}}</th>
                                    {{-- <th>{{__('Način rešavanja')}}</th> --}}
                                    <th>{{__('Status')}}</th>
                                    <th class="no-sort w-20">{{__('Akcije')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($complaints as $c)
                                <tr id='trcomplaint{{$c->id}}'><a id='complaint{{$c->id}}'></a>
                                    <td id='tdcomplaint{{$c->id}}' class="text-center">{{ $c->name }}</td>
                                    <td class="text-center">{{ date('d.m.Y', strtotime($c->submission_date)) }}</td>
                                    {{--  <td class="text-center">{{ Str::length($c->description) < 35 ? $c->description : Str::limit($c->description, 35) }}
                                    </td> --}}
                                    <td class="text-center">
                                        @forelse($c->documents as $d)
                                        <form class="inline" action="{{ route('document.preview') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="folder"
                                                value="{{ Str::snake(Auth::user()->currentTeam->name).'/'.$d->doc_category }}">
                                            <input type="hidden" name="file_name" value="{{ $d->file_name }}">
                                            <button data-toggle="tooltip" data-placement="top"
                                                title="{{__('Pregled dokumenta')}}"
                                                class="button text-primary cursor-pointer text-sm" type="submit"
                                                formtarget="_blank">{{ $d->file_name }}</button>
                                        </form><br>
                                        @empty
                                        /
                                        @endforelse
                                    </td>
                                    <td class="text-center">{{ $c->sector->name }}</td>
                                    <td class="text-center">{{ $c->accepted == 1 ? __("DA") : __("NE") }}</td>
                                    <td class="text-center">
                                        {{ $c->deadline_date != null ? date('d.m.Y', strtotime($c->deadline_date)) : "/" }}
                                    </td>
                                    <td class="text-center">
                                        {{ $c->users ? $c->users()->pluck('name')->implode(', ') : '/' }}</td>
                                    <td class="text-center">{{ ($c->status == '1') ? __('Otvorena') : __('Zatvorena') }}
                                    </td>
                                    <td class="text-center">
                                        <button data-toggle="tooltip" data-placement="top"
                                            title="{{ __('Pregled reklamacije') }}"
                                            class="text-blue-700 hover:text-blue-900"
                                            onclick="showComplaint({{ $c->id }})"><i class="fas fa-eye"></i></button>
                                        <a href="{{route('complaints.print',$c->id)}}" target="_blank"
                                            data-toggle="tooltip" data-placement="top"
                                            class="text-green-400 hover:text-green-600" title="{{__('Odštampaj')}}"><i
                                                class="fas fa-print"></i>
                                        </a>
                                        @canany(['update', 'delete'], $c)
                                        <a data-toggle="tooltip" data-placement="top"
                                            title="{{__('Izmena reklamacije')}}"
                                            href="{{ route('complaints.edit', $c->id) }}"><i
                                                class="fas fa-edit"></i></a>
                                        <form class="inline" id="delete-form-{{ $c->id }}"
                                            action="{{ route('complaints.destroy', $c->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="button" class="text-red-600 cursor-pointer hover:text-red-800"
                                                data-toggle="tooltip" data-placement="top"
                                                title="{{__('Brisanje reklamacije')}}"
                                                onclick="confirmDeleteModal({{ $c->id }})"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                        @endcanany
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

    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="px-6 py-4">
                    <div class="text-lg">{{__('Brisanje reklamacije')}}</div>
                    <div class="mt-4">{{__('Da li ste sigurni?')}}</div>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <button type="button"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150"
                        data-dismiss="modal">{{__('Odustani')}}</button>
                    <a
                        class="btn-ok hover:no-underline cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150 ml-2">{{__('Obriši')}}</a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<script>
    var myRe = /\bcomplaints\b/g;

    if(myRe.test(window.location.href)){
    window.addEventListener('popstate', function (event) {
        location.reload();
    });
    }

    let href = window.location.href;
    id = href.split('#')[1];
    if(id){
        let e = document.getElementById('tr' + id);
        let i = document.getElementById('td' + id);
        i.innerHTML = '<i class="fas fa-hand-point-right"></i> ' +  i.innerHTML;
        e.style = "background:#d8ffcc;";
    }

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
        "columnDefs": [
            {
                "targets": 'no-sort',
                "orderable": false,
            },
            {
                "type": 'de_date',
                targets: [1, 5]
            }
        ],
        "order": [[ 5, "asc" ]]
    });

    function confirmDeleteModal($id){
        let id = $id;
        $('#confirm-delete-modal').modal();
        $('#confirm-delete-modal').on('click', '.btn-ok', function(e) {
            let form = $('#delete-form-'+id);
            form.submit();
        });
    }

    $('[data-toggle="tooltip"]').tooltip();

    function showComplaint(id){
        axios.get('/complaints/'+id)
            .then((response) => {

                let modal = `
                    <div class="modal fade" id="showData-${ id }" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content rounded-0">
                                <div class="modal-header text-center">
                                    <h5 class="modal-title font-weight-bold">${ response.data.name }</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row text-sm">
                                        <div class="col-sm-5 mt-1 border-bottom font-weight-bold"><p>{{ __('Oznaka') }}</p></div>
                                        <div class="col-sm-7 mt-1 border-bottom"><p>${ response.data.name }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Opis') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.description }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Datum podnošenja') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ new Date(response.data.submission_date).toLocaleDateString('sr-SR', { timeZone: 'CET' }) }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Proces na koji se reklamacija odnosi') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.sector.name }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Opravdana / prihvaćena') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.accepted == 1 ? "{{ __('Da') }}":"{{ __('Ne') }}" }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Rok za realizaciju') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${response.data.deadline_date ? new Date (response.data.deadline_date).toLocaleDateString('sr-SR', { timeZone: 'CET' }) : '/' }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Lice odgovorno za rešavanje') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>`;
                                        $.each(response.data.users, function (j, person){
                                            modal += person.name;
                                            if(j != response.data.users.length-1){
                                                modal += ", ";
                                            }
                                        });
                                        modal += `</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Način rešavanja') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.way_of_solving ? response.data.way_of_solving :'/' }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Status') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.status == 1 ? "{{ __('Otvorena') }}":"{{ __('Zatvorena') }}" }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Datum zatvaranja') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${response.data.closing_date ? new Date (response.data.closing_date).toLocaleDateString('sr-SR', { timeZone: 'CET' }) : '/' }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.user.name  }</p></div>
                                    </div>
                                </div>
                                <div class="px-6 py-4 bg-gray-100 text-right">
                                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Zatvori') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $("body").append(modal);
                $('#showData-'+id).modal();
            })
            .catch((error) => {
                console.log(error)
            })
    }

</script>
