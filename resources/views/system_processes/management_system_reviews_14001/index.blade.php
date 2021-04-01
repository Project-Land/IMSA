<x-app-layout>
    @push('scripts')
        <!-- Datatable -->
        <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Zapisnici sa preispitivanja') }}
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
                    <div class="row">
                        <div class="col-sm-4">
                            @can('create', App\Models\ManagementSystemReview::class)
                            <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('management-system-reviews.create') }}"><i class="fas fa-plus"></i> {{ __('Kreiraj zapisnik')}}</a>
                            @endcan
                        </div>
                        <div class="col-sm-8">
                            <form class="form-inline">
                                <label for="year" class="mr-3 text-xs md:text-base">{{__('Godina')}}</label>
                                <select name="year" id="reviews-year" class="appearance-none w-1/3 sm:w-1/4 text-xs md:text-base mr-2 block border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                    <option value="all">{{ __('Sve godine') }}</option>
                                    @foreach(range(2020, date('Y')+10) as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>{{__('Zapisnik')}}</th>
                                    <th class="no-sort">{{__('Akcije')}}</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach($msr as $m)
                                <tr>
                                    <td class="text-center">{{__('Zapisnik sa preispitivanja')}} {{ $m->year }}</td>
                                    <td class="text-center">
                                        <button data-toggle="tooltip" data-placement="top" title="{{__('Pregled zapisnika')}}" class="text-blue-700 hover:text-blue-900" onclick="showMSR({{ $m->id }})"><i class="fas fa-eye"></i></button>
                                        <a href="{{route('management-system-reviews.print',$m->id)}}" target="_blank" data-toggle="tooltip" data-placement="top" class="text-green-400 hover:text-green-600" title="{{__('Odštampaj')}}" ><i class="fas fa-print"></i></a>
                                        @canany(['update'], $m)
                                        <a data-toggle="tooltip" data-placement="top" title="{{__('Izmena zapisnika')}}" href="{{ route('management-system-reviews.edit', $m->id) }}"><i class="fas fa-edit"></i></a>
                                        @endcanany
                                        <a href="{{ route('msr.export', $m->id) }}" class="text-green-500 hover:text-green-700" data-toggle="tooltip" data-placement="top" title="{{__('Eksport zapisnika u excel')}}"><i class="fas fa-file-export"></i></a>
                                        @canany(['delete'], $m)
                                        <form class="inline" id="delete-form-{{ $m->id }}" action="{{ route('management-system-reviews.destroy', $m->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button data-toggle="tooltip" data-placement="top" title="{{__('Brisanje zapisnika')}}" class="text-red-600 cursor-pointer hover:text-red-800" type="button" onclick="confirmDeleteModal({{ $m->id }})"><i class="fas fa-trash"></i></button>
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
                    <div class="text-lg">{{__('Brisanje zapisnika sa preispitivanja')}}</div>
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

    function showMSR(id){
        axios.get('/management-system-reviews/'+id)
            .then((response) => {
                let modal =`
                    <div class="modal fade" id="showMSR-${ id }" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content rounded-0">
                                <div class="modal-header">
                                    <h5 class="modal-title font-weight-bold">{{__('Zapisnik sa preispitivanja')}} ${ response.data.year }</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row text-sm">
                                        <div class="col-sm-5 mt-1 border-bottom font-weight-bold text-sm"><p>{{__('Učestvovali u preispitivanju')}}</p></div>
                                        <div class="col-sm-7 mt-1 border-bottom"><p>${ response.data.participants }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Status mera iz prethodnog preispitivanja')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measures_status }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Promene u eksternim i internim pitanjima koje su relevantne za sistem menadžmenta')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.internal_external_changes }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Potrebe i očekivanja zainteresovanih strana i obaveze za usklađenost')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.customer_satisfaction }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Aspekti životne sredine')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.environmental_aspects }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Obim ispunjenosti ciljeva')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.objectives_scope }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Neusaglašenosti i korektivne mere')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.inconsistancies_corrective_measures }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Rezultati praćenja i merenja')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.monitoring_measurement_results }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Ispunjenost obaveza za usklađenost')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.fulfillment_of_obligations }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Rezultati internih provera')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.checks_results }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Rezultati eksternih provera')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.checks_results_desc }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Adekvatnost resursa')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.resource_adequacy }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Efektivnost mera koje se odnose na rizike i prilike')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measures_effectiveness }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Komunikacija i prigovori iz domena životne sredine')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.communication_and_objections }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Prilike za poboljšanja')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.improvement_opportunities ? response.data.improvement_opportunities : "/" }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Pogodnost, adekvatnost i efektivnost sistema menadžmenta životnom sredinom')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.cae ? response.data.cae : "/" }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Prilike za stalna poboljšanja')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.continous_improvement_opportunities ? response.data.continous_improvement_opportunities : "/" }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Potrebe za izmenama u sistemu menadžmenta')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.needs_for_change ? response.data.needs_for_change : "/" }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Mere, u slučaju da ciljevi životne sredine nisu ispunjeni')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measures_optional ? response.data.measures_optional : "/" }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Prilike za poboljšanje i integrisanje sa drugim procesima i sistemima menadžmenta')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.opportunities ? response.data.opportunities : "/" }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Eventualne posledice po strateško usmerenje organizacije')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.consequences ? response.data.consequences : "/" }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{__('Kreirao')}}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.user.name }</p></div>
                                    </div>
                                </div>
                                <div class="px-6 py-4 bg-gray-100 text-right">
                                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{__('Zatvori')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $("body").append(modal);
                $('#showMSR-'+id).modal();
            })
            .catch((error) => {
                console.log(error)
            })
    }

    function deleteSingleReview(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let url = "/management-system-reviews/delete/"+id;

        if(confirm('{{__("Da li ste sigurni?")}}')){
            $.ajax({
                type: "delete",
                url: url,
                data: {
                    id: id
                },
                success: function(result) {
                    alert('{{__("Zapisnik je uspešno uklonjen")}}');
                    location.reload();
                },
                error: function(result) {
                    alert('error', result);
                }
            });
        }
    }

    $('#reviews-year').change( () => {
        let year = $('#reviews-year').val();
        const data = {'year': year}
        axios.post('/management-system-reviews/get-data', { data })
        .then((response) => {
            if(response.data.length == 0){
                $('#table-body').html('<td colspan="2" class="dataTables_empty" valign="top">{{__("Nema podataka")}}</td>');
            }
            else{
                let allData = "";
                $.each(response.data, function (i, item){
                    let row = `
                        <tr>
                            <td class="text-center">{{__('Zapisnik sa preispitivanja')}} ${ item.year }</td>
                            <td class="text-center">
                                <button class="text-blue-700 hover:text-blue-900" onclick="showMSR(${ item.id })"><i class="fas fa-eye"></i></button>
                                <a href="/management-system-reviews/print/${ item.id }" target="_blank" data-toggle="tooltip" data-placement="top" class="text-green-400 hover:text-green-600" title="{{__('Odštampaj')}}" ><i class="fas fa-print"></i></a>
                                <span class="${ item.isAdmin === false ? 'd-none' : '' }">
                                    <a href="/management-system-reviews/${ item.id }/edit"><i class="fas fa-edit"></i></a>
                                </span>
                                <a href="/msr-export/${ item.id }" class="text-green-500 hover:text-green-700" data-toggle="tooltip" data-placement="top" title="{{__('Eksport zapisnika u excel')}}"><i class="fas fa-file-export"></i></a>
                                <span class="${ item.isAdmin === false ? 'd-none' : '' }">
                                    <a class="cursor-pointer text-red-600 hover:text-red-800" onclick="deleteSingleReview(${ item.id })" data-id="${ item.id }"><i class="fas fa-trash"></i></a>
                                </span>
                            </td>
                        </tr>
                    `;
                    allData += row;
                });
                $('#table-body').html(allData)
            }
        }, (error) => {
            console.log(error);
        })
    });

    $('[data-toggle="tooltip"]').tooltip();

    function confirmDeleteModal($id){
        let id = $id;
        $('#confirm-delete-modal').modal();
        $('#confirm-delete-modal').on('click', '.btn-ok', function(e) {
            let form = $('#delete-form-'+id);
            form.submit();
        });
    }

</script>
