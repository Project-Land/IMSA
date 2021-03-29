<x-app-layout>
    @push('scripts')
        <!-- Datatable -->
        <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ Session::get('standard_name') }} - {{ __('Istraživanje incidenata') }}
        </h2>
    </x-slot>

    <div class="row mt-1">
        <div class="col">
            @if(Session::has('status'))
                <x-alert :type="Session::get('status')[0]" :message="Session::get('status')[1]"/>
            @endif
        </div>
    </div>

    <div class="row mt-3">

        <div class="col">

            <div class="card">
                <div class="card-header">
                    @can('create', App\Models\Accident::class)
                    <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('accidents.create') }}"><i class="fas fa-plus"></i> {{__('Kreiraj izveštaj incidenta')}}</a>
                    @endcan
                    <a class="inline-block float-right text-xs md:text-base bg-green-500 hover:bg-green-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('accidents.export') }}"><i class="fas fa-file-export"></i> {{ __('Excel') }}</a>
                </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>{{__('Datum')}}</th>
                                    <th>{{__('Ime i prezime povređenog')}}/{{ __('aktera incidenta') }}</th>
                                    <th>{{__('Tip povrede')}}/{{ __('incidenta') }}</th>
                                    <th class="no-sort">{{__('Izveštaj')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accidents as $accident)
                                <tr>
                                    <td class="text-center">{{ date('d.m.Y',strtotime($accident->injury_datetime)) }}</td>
                                    <td class="text-center">{{ $accident->name }}</td>
                                    <td class="text-center">
                                        @if($accident->injury_type == "mala")
                                           {{ __('Laka') }}
                                        @elseif($accident->injury_type == "velika")
                                            {{ __('Teška') }}
                                        @else
                                            {{ __('Incident bez povrede') }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                    <a data-toggle="tooltip" data-placement="top" title="{{__('Pregled izveštaja')}}" class="text-blue-700 hover:text-blue-900" onclick="showAccident({{ $accident->id }})"><i class="fas fa-eye"></i></a>
                                    <a
                                        href="{{route('accidents.print',$accident->id)}}" target="_blank" data-toggle="tooltip" data-placement="top" class="text-green-400 hover:text-green-600" title="{{__('Odštampaj')}}" ><i class="fas fa-print"></i>
                                        </a>
                                        @canany(['update', 'delete'], $accident)
                                        <a data-toggle="tooltip" data-placement="top" title="{{__('Izmena izveštaja incidenta')}}" href="{{ route('accidents.edit', $accident->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" id="delete-form-{{ $accident->id }}" action="{{ route('accidents.destroy', $accident->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="button" class="text-red-600 cursor-pointer hover:text-red-800" data-toggle="tooltip" data-placement="top" title="{{__('Brisanje izveštaja incidenta')}}" onclick="confirmDeleteModal({{ $accident->id }})"><i class="fas fa-trash"></i></button>
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

    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="px-6 py-4">
                    <div class="text-lg">{{__('Brisanje izveštaja incidenta')}}</div>
                    <div class="mt-4">{{__('Da li ste sigurni?')}}</div>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{__('Odustani')}}</button>
                    <a class="btn-ok hover:no-underline cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150 ml-2">{{__('Obriši')}}</a>
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
        "order": [[ 0, "desc" ]]
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

    function showAccident(id){
        axios.get('/accidents/'+id)
            .then((response) => {
                let modal = `<div class="modal fade" id="showData-${ id }" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header text-center">
                                            <h5 class="modal-title font-weight-bold">{{ __('Izveštaj') }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row text-sm">
                                                <div class="col-sm-5 mt-1 border-bottom font-weight-bold"><p>{{ __('Prezime, ime povređenog') }}/{{ __('aktera incidenta') }}</p></div>
                                                <div class="col-sm-7 mt-1 border-bottom"><p>${ response.data.name.split(' ').reverse().join(' ') }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Poslovi i zadaci koje obavlјa') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.jobs_and_tasks_he_performs }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Datum i vreme povrede') }}/{{ __('incidenta') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.injury_datetime != null ? new Date(response.data.injury_datetime).toLocaleString('sr-SR', { timeZone: 'CET' }) : "/" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Uzrok povrede') }}/{{ __('incidenta') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.injury_cause }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{!! __('KRATAK OPIS POVREDE/INCIDENTA <br>(kako je došlo do povrede/incidenta - u fazama)') !!}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.injury_description }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Šta je greška?') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.error }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Po čijem je nalogu radio?') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.order_from }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Da li je obučen za rad i upoznat sa opasnostima i rizicima za te poslove?') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.dangers_and_risks }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Da li je koristio predviđena lična zaštitna sredstva i opremu i koju?') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.protective_equipment }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Da li je radio na poslovima sa povećanim rizikom?') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.high_risk_jobs }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Da li ispunjava sve uslove za te poslove?') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.job_requirements }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{!! __('Podaci o svedoku-očevicu<br>- Ime, prezime i broj telefona (ako je bilo)') !!}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.witness ?? '/' }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{!! __('Podaci o neposrednom rukovodiocu povređenog/aktera incidenta<br>- Ime, prezime i radno mesto') !!}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.supervisor }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Datum i vreme prijave povrede/incidenta') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ new Date(response.data.injury_report_datetime).toLocaleString('sr-SR', { timeZone: 'CET' }) }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Zapažanje/komentar') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.comment ?? '/' }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.user.name }</p></div>
                                            </div>
                                        </div>
                                        <div class="px-6 py-4 bg-gray-100 text-right">
                                            <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Zatvori') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                $("body").append(modal);
                $('#showData-'+id).modal();
            })
            .catch((error) => {
                console.log(error)
            })
    }

</script>
