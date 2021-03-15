<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Aspekti životne sredine') }}
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
                    @can('create', App\Models\EnvironmentalAspect::class)
                        <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('environmental-aspects.create') }}"><i class="fas fa-plus"></i> {{ __('Dodaj aspekt')}}</a>
                    @endcan
                </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>{{ __('Proces')}}</th>
                                    <th>{{ __('Otpad / Fizičko-hemijska pojava')}}</th>
                                    <th>{{ __('Aspekt')}}</th>
                                    <th>{{ __('Uticaj')}}</th>
                                    <th>{{ __('Karakter otpada')}}</th>
                                    <th>{{ __('Verovatnoća pojavljivanja')}}</th>
                                    <th>{{ __('Verovatnoća otkrivanja')}}</th>
                                    <th>{{ __('Ozbiljnost posledica')}}</th>
                                    <th>{{ __('Procenjeni uticaj')}}</th>
                                    <th class="no-sort">{{__('Akcije')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($environmental_aspects as $ea)
                                <tr>
                                    <td class="text-center">{{ $ea->process }}</td>
                                    <td class="text-center">{{ $ea->waste }}</td>
                                    <td class="text-center">{{ $ea->aspect }}</td>
                                    <td class="text-center">{{ $ea->influence }}</td>
                                    <td class="text-center">{{ $ea->waste_type === 1 ? __('Opasan') : __('Neopasan') }}</td>
                                    <td class="text-center">{{ $ea->probability_of_appearance }}</td>
                                    <td class="text-center">{{ $ea->probability_of_discovery }}</td>
                                    <td class="text-center">{{ $ea->severity_of_consequences }}</td>
                                    <td class="text-center {{ $ea->estimated_impact >= 8 ? "text-red-600" : "" }}">{{ $ea->estimated_impact }}</td>
                                    <td class="text-center">
                                        <button data-toggle="tooltip" data-placement="top" title="{{__('Pregled')}}" class="button text-primary" onclick="showModal({{ $ea->id }})"><i class="fas fa-eye"></i></button>
                                        @canany(['update', 'delete'], $ea)
                                        <a data-toggle="tooltip" data-placement="top" title="{{ __('Izmena aspekta životne sredine')}}" href="{{ route('environmental-aspects.edit', $ea->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" id="delete-form-{{ $ea->id }}" action="{{ route('environmental-aspects.destroy', $ea->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="text-red-600 cursor-pointer hover:text-red-800" type="button" data-toggle="tooltip" data-placement="top" title="{{ __('Brisanje aspekta životne sredine')}}" onclick="confirmDeleteModal({{ $ea->id }})"><i class="fas fa-trash"></i></button>
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
                    <div class="text-lg">{{ __('Brisanje aspekta životne sredine')}}</div>
                    <div class="mt-4">{{ __('Da li ste sigurni?')}}</div>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Odustani')}}</button>
                    <a class="btn-ok hover:no-underline cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150 ml-2">{{ __('Obriši')}}</a>
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
    });

    function confirmDeleteModal($id){
        let id = $id;
        $('#confirm-delete-modal').modal();
        $('#confirm-delete-modal').on('click', '.btn-ok', function(e) {
            let form = $('#delete-form-'+id);
            form.submit();
        });
    }

    function showModal(id){
        axios.get('/environmental-aspects/'+id)
            .then((response) => {
                let modal = `
                    <div class="modal fade" id="showModal-${ id }" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content rounded-0">
                                <div class="modal-header">
                                    <h5 class="modal-title font-weight-bold">{{__('Aspekt životne sredine')}} "${ response.data.process }"</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row text-sm">
                                        <div class="col-sm-5 mt-1 border-bottom font-weight-bold text-sm"><p>{{ __('Proces') }}</p></div>
                                        <div class="col-sm-7 mt-1 border-bottom"><p>${ response.data.process }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{ __('Otpad / Fizičko-hemijska pojava') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.waste }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{ __('Aspekt') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.aspect }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{ __('Uticaj') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.influence }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{ __('Karakter otpada') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.waste_type == 1 ? "{{ __('Opasan') }}" : "{{ __('Neopasan') }}" }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{ __('Verovatnoća pojavljivanja') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.probability_of_appearance }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{ __('Verovatnoća otkrivanja') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.probability_of_discovery }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{ __('Ozbiljnost posledica') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.severity_of_consequences }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{ __('Procenjeni uticaj') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p ${ response.data.estimated_impact >=9 ? "class='text-red-600'":"" }>${ response.data.estimated_impact }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>{{ __('Kreirao') }}</p></div>
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
                $('#showModal-'+id).modal();
            })
            .catch((error) => {
                console.log(error)
            })
    }

    $('[data-toggle="tooltip"]').tooltip();

</script>
