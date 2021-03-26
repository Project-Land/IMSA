<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Zadovoljstvo korisnika') }}
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
                    @can('create', App\Models\CustomerSatisfaction::class)
                        @if(!$poll->isNotEmpty())
                            <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('customer-satisfaction-poll.create') }}"><i class="fas fa-plus"></i> {{ __('Kreiraj anketu')}}</a>
                        @else
                            <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3 mr-2" href="{{ route('customer-satisfaction.create') }}"><i class="fas fa-poll"></i> {{ __('Popuni anketu')}}</a>
                            <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('customer-satisfaction-poll.edit', \Auth::user()->currentTeam->id) }}"><i class="fas fa-edit"></i> {{ __('Izmeni anketu')}}</a>
                        @endempty
                    @endcan
                    <a class="inline-block float-right text-xs md:text-base bg-green-500 hover:bg-green-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('customer-satisfaction.export') }}"><i class="fas fa-file-export"></i> {{ __('Excel') }}</a>
                </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>{{ __('Klijent')}}</th>
                                    @for($i = 0; $i < $poll->count(); $i++)
                                        <th>{{ $poll[$i]->name }}</th>
                                    @endfor
                                    @if($poll->count() <= 4)
                                        <th>{{ __('Napomena') }}</th>
                                        <th>{{ __('Datum') }}</th>
                                    @endif
                                    <th>{{ __('Prosek') }}</th>
                                    <th class="no-sort">{{ __('Akcije')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cs as $row)
                                <tr class="text-center">
                                    <td>{{ $row->customer }}</td>
                                    @foreach($poll as $po)
                                        <td>{{ $row->{$po->column_name} ?? "/" }}</td>
                                    @endforeach

                                    @if($poll->count() <= 4)
                                        <td>{{ $row->comment ?? "/" }}</td>
                                        <td>{{ date('d.m.Y', strtotime($row->created_at)) }}</td>
                                    @endif
                                    <td class="font-bold">{{ $row->average() }}</td>
                                    <td>

                                        <button data-toggle="tooltip" data-placement="top" title="{{ __('Pregled ankete') }}" class="button text-primary" onclick="showPoll({{ $row->id }})"><i class="fas fa-eye"></i></button>
                                        <a
                                        href="{{route('customer-satisfaction.print',$row->id)}}" target="_blank" data-toggle="tooltip" data-placement="top" class="text-green-400" title="{{__('Odštampaj')}}" ><i class="fas fa-print"></i>
                                        </a>
                                        @canany(['update', 'delete'], $row)
                                        <a data-toggle="tooltip" data-placement="top" title="{{__('Izmena ankete')}}" href="{{ route('customer-satisfaction.edit', $row->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" id="delete-form-{{ $row->id }}" action="{{ route('customer-satisfaction.destroy', $row->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="button" class="text-red-600 cursor-pointer hover:text-red-800" data-toggle="tooltip" data-placement="top" title="{{__('Brisanje ankete')}}" onclick="confirmDeleteModal({{ $row->id }})"><i class="fas fa-trash"></i></button>
                                        </form>
                                        @endcanany
                                    </td>
                                </tr>
                                @endforeach
                                @if($cs->count())
                                <tr class="font-bold text-center">
                                    <td class="bg-green-200">{{__('Prosek')}}</td>
                                    @foreach($poll as $po)
                                        <td class="bg-green-200">@if($cs->count()) {{ ($cs->sum($po->column_name)/$cs[0]->columnCount($po->column_name)) == 0 ? "/" : round($cs->sum($po->column_name)/$cs[0]->columnCount($po->column_name), 1) }} @endif</td>
                                    @endforeach
                                    @if($poll->count() <= 4)
                                    <td class="bg-green-200"></td>
                                    <td class="bg-green-200"></td>
                                    @endif
                                    <td class="bg-green-200"></td>
                                    <td class="bg-green-200"></td>
                                </tr>
                                @endif
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
                    <div class="text-lg">{{ __('Brisanje unosa ankete')}}</div>
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
        "order": [[ 6, "desc" ]]
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

    function showPoll(id){
        axios.get('/customer-satisfaction/'+id)
            .then((response) => {
                let modal = `
                    <div class="modal fade" id="showData-${ id }" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content rounded-0">
                                <div class="modal-header text-center">
                                    <h5 class="modal-title font-weight-bold">{{ __('Zadovoljstvo korisnika') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-5 mt-1 border-bottom font-weight-bold"><p>{{ __('Klijent') }}</p></div>
                                        <div class="col-sm-7 mt-1 border-bottom"><p>${ response.data.customer }</p></div>
                                `;

                                for(col of response.data.columns){
                                    modal += `<div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>${col.name}</p></div>
                                    <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data[col.column_name] ?? "/" }</p></div>`;
                                }
                                modal += `
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Prosek') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p class="font-bold">${ response.data.average }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Napomena') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.comment ?? "/" }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Datum') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ new Date(response.data.created_at).toLocaleString('sr-SR', { timeZone: 'CET' }) }</p></div>
                                        <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }}</p></div>
                                        <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.user.name }</p></div>
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
