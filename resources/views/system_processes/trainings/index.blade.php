<x-app-layout>
    @push('scripts')
        <!-- Datatable -->
        <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    @endpush

    <x-slot name="header">
    <div class="flex flex-row justify-between">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Godišnji planovi obuka') }}
        </h2>
        @include('includes.video')
    </div>
    </x-slot>

    <div class="row mt-1">
        <div class="col">
            @if(Session::has('status'))
                <x-alert :type="Session::get('status')[0]" :message="__(Session::get('status')[1])"/>
            @endif
        </div>
    </div>

    <div class="row mt-3">

        <div class="col">

            <div class="card">
                <div class="card-header">
                    <div class="flex flex-wrap justify-between">
                        <div class="w-full sm:flex-1">
                            @can('create', App\Models\Training::class)
                                <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('trainings.create') }}"><i class="fas fa-plus"></i> {{ __('Dodaj obuku') }}</a>
                            @endcan
                        </div>
                        <div class="w-full sm:flex-1">
                            <form class="form-inline">
                                <label for="year" class="mr-3 text-xs md:text-base">{{ __('Godina') }}</label>
                                <select name="year" id="trainings-year" class="appearance-none w-1/3 sm:w-2/4 text-xs md:text-base mr-2 block border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                    <option value="all">{{ __('Sve godine') }}</option>
                                    @foreach(range(2020, date('Y')+10) as $year)
                                        <option value="{{ $year }}" {{ date('Y') == $year ? "selected" : "" }} >{{ $year }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="w-full sm:flex-1">
                            <a class="inline-block sm:float-right text-xs md:text-base bg-green-500 hover:bg-green-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('trainings.export') }}"><i class="fas fa-file-export"></i> {{ __('Excel') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>{{ __('Godina') }}</th>
                                    <th>{{ __('Naziv') }}</th>
                                    <th>{{ __('Vrsta') }}</th>
                                    <th>{{ __('Opis') }}</th>
                                    <th>{{ __('Br. zaposlenih - planirano') }}</th>
                                    <th>{{ __('Termin') }} / {{ __('Mesto') }}</th>
                                    <th>{{ __('Resursi') }}</th>
                                    <th>{{ __('Br. zaposlenih - realizovano') }}</th>
                                    <th>{{ __('Ocena efekata obuke') }}</th>
                                    <th class="no-sort w-20">{{ __('Akcije') }}</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach($trainingPlans as $tp)
                                <tr>
                                    <td class="text-center">{{ $tp->year }}</td>
                                    <td class="text-center">{{ $tp->name }}</td>
                                    <td class="text-center">{{ __($tp->type) }}</td>
                                    <td class="text-center">{{ Str::length($tp->description) < 35 ? $tp->description : Str::limit($tp->description, 35) }}</td>
                                    <td class="text-center">{{ $tp->num_of_employees }}</td>
                                    <td class="text-center">{{ date('d.m.Y', strtotime($tp->training_date)) }} {{ __('u') }} {{ date('H:i', strtotime($tp->training_date)) }}, {{ $tp->place }}</td>
                                    <td class="text-center">{{ Str::length($tp->resources) < 35 ? $tp->resources : Str::limit($tp->resources, 35) }}</td>
                                    <td class="text-center">{{ $tp->final_num_of_employees? : '/' }}</td>
                                    <td class="text-center">{{ $tp->rating? : '/' }}</td>
                                    <td class="text-center">
                                        <button data-toggle="tooltip" data-placement="top" title="{{ __('Prikaz obuke') }}" class="text-blue-700 hover:text-blue-900" onclick="showTraining({{ $tp->id }})"><i class="fas fa-eye"></i></button>
                                        <a
                                            href="{{route('trainings.print',$tp->id)}}" target="_blank" data-toggle="tooltip" data-placement="top" class="text-green-400 hover:text-green-600" title="{{__('Odštampaj')}}" ><i class="fas fa-print"></i>
                                        </a>
                                        @canany(['update', 'delete'], $tp)
                                        <a data-toggle="tooltip" data-placement="top" title="{{ __('Izmena obuke') }}" href="{{ route('trainings.edit', $tp->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" id="delete-form-{{ $tp->id }}" action="{{ route('trainings.destroy', $tp->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="text-red-600 cursor-pointer hover:text-red-800" type="button" data-toggle="tooltip" data-placement="top" title="{{ __('Brisanje obuke') }}" onclick="confirmDeleteModal({{ $tp->id }})"><i class="fas fa-trash"></i></button>
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
                    <div class="text-lg">{{ __('Brisanje obuke') }}</div>
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

    $('[data-toggle="tooltip"]').tooltip();

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
        //"order": [[ 6, "desc" ]]
    });

    function deleteTraining(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let url = "/trainings/delete/"+id;

        if(confirm('{{ __("Da li ste sigurni?") }}')){
            $.ajax({
                type: "delete",
                url: url,
                data: {
                    id: id
                },
                success: function(result) {
                    alert('{{ __("Godišnji plan obuke uspešno uklonjen") }}');
                    location.reload();
                },
                error: function(result) {
                    alert('error', result);
                }
            });
        }
    }

    $('#trainings-year').change( () => {
        let year = $('#trainings-year').val();
        const data = {'year': year}
        axios.post('/trainings/get-data', { data })
        .then((response) => {
            if(response.data.length == 0){
                $('#table-body').html('<td colspan="10" class="dataTables_empty" valign="top">{{ __("Nema podataka") }}</td>');
            }
            else{
                let allData = "";
                $.each(response.data, function (i, item){
                    let row = `
                        <tr>
                            <td class="text-center">${ item.year }</td>
                            <td class="text-center">${ item.name }</td>
                            <td class="text-center">{{ __('${ item.type }') }}</td>
                            <td class="text-center">${ item.description.length < 35 ? item.description : item.description.substr(0, 35) + "..." }</td>
                            <td class="text-center">${ item.num_of_employees }</td>
                            <td class="text-center">${ new Date(item.training_date).toLocaleString('sr-SR', { timeZone: 'CET' }) }, ${ item.place }</td>
                            <td class="text-center">${ item.resources }</td>
                            <td class="text-center">${ item.final_num_of_employees != null ? item.final_num_of_employees : "/" }</td>
                            <td class="text-center">${ item.rating != null ? item.rating : "/" }</td>
                            <td class="text-center">
                                <button data-toggle="tooltip" data-placement="top" title="{{ __('Prikaz obuke') }}" class="text-blue-700 hover:text-blue-900" onclick="showTraining(${ item.id })"><i class="fas fa-eye"></i></button>
                                <a href="/trainings/print/${ item.id }" target="_blank" data-toggle="tooltip" data-placement="top" class="text-green-400 hover:text-green-600" title="{{__('Odštampaj')}}"><i class="fas fa-print"></i></a>
                                <span class="${ item.isAdmin === false ? 'd-none' : '' }">
                                    <a data-toggle="tooltip" data-placement="top" title="{{ __('Izmena obuke') }}" href="/trainings/${ item.id }/edit"><i class="fas fa-edit"></i></a>
                                    <a class="text-red-600 cursor-pointer hover:text-red-700" data-toggle="tooltip" data-placement="top" title="{{ __('Brisanje obuke') }}" id="delete-training" onclick="deleteTraining(${ item.id })" data-id="${ item.id }"><i class="fas fa-trash"></i></a>
                                </span>
                            </td>
                        </tr>
                    `;
                    allData += row;
                });
                $('[data-toggle="tooltip"]').tooltip();
                $('#table-body').html(allData)
            }
        }, (error) => {
            console.log(error);
        })
    });

    function showTraining(id){
        axios.get('/trainings/'+id)
            .then((response) => {
                let documents = $.each(response.data.docArray, function(i, item){
                    response.data.docArray[i].file_name
                })


                let docsBlock = "<div id='docsBlock'>";
                Object.keys(documents).forEach(key => {
                    docsBlock += `<form class="inline" action="{{ route('document.preview') }}" method="POST" target="_blank">
                        @csrf
                        <input type="hidden" name="folder" value="${ response.data.company }/training">
                        <input type="hidden" name="file_name" value="${ documents[key].file_name }">
                        <button data-toggle="tooltip" data-placement="top" title="{{__('Pregled dokumenta')}}" class="text-blue-500 hover:text-blue-700 curosr-pointer" type="submit">${ documents[key].file_name }</button>
                    </form><br>`;

                    for(user of documents[key].users){
                        docsBlock += `<span>${ user.name }</span>, `;
                    }

                    docsBlock += "<hr>";
                })

                for(userWithoutDoc of response.data.users_without_document){
                    docsBlock += `<span>${ userWithoutDoc.name }</span>, `;
                }

                docsBlock += "</div>";
                let modal = `<div class="modal fade" id="showData-${ id }" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header text-center">
                                            <h5 class="modal-title font-weight-bold">${ response.data.name }</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row text-sm">
                                                <div class="col-sm-5 mt-1 border-bottom font-weight-bold"><p>{{ __('Naziv') }}</p></div>
                                                <div class="col-sm-7 mt-1 border-bottom"><p>${ response.data.name }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Vrsta') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>{{ __('${ response.data.type }') }}</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Opis') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.description }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Broj zaposlenih - planirano') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.num_of_employees }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Termin') }} / {{ __('Mesto') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ new Date(response.data.training_date).toLocaleDateString('sr-SR', { timeZone: 'CET' }) } {{ __('u') }} ${ new Date(response.data.training_date).toLocaleTimeString('sr-SR', { timeZone: 'CET', timeStyle: 'short' }) }, ${ response.data.place }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Resursi') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.resources }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Broj zaposlenih - realizovano') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.final_num_of_employees != null ? response.data.final_num_of_employees : "/" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Ocena efekata obuke') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.rating != null ? response.data.rating : "/" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Učesnici') }}</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom">
                                                    ${ documents.length === 0 ? "/" : docsBlock }
                                                </div>
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

    function confirmDeleteModal($id){
        let id = $id;
        $('#confirm-delete-modal').modal();
        $('#confirm-delete-modal').on('click', '.btn-ok', function(e) {
            let form = $('#delete-form-'+id);
            form.submit();
        });
    }

</script>
