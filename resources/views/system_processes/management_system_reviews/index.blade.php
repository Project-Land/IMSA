<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Zapisnik sa preispitivanja') }}
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

    <div class="row mt-3">

        <div class="col">

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-4">
                            <a class="btn btn-info" href="{{ route('management-system-reviews.create') }}"><i class="fas fa-plus"></i> Kreiraj zapisnik</a>
                        </div>
                        <div class="col-sm-8">
                            <form class="form-inline">
                                <label for="year" class="mr-3">Godina</label>
                                <select name="year" id="year" class="form-control w-25 mr-2">
                                    @foreach(range(date('Y')-10, date('Y')+10) as $year)
                                        <option value="{{ $year }}" {{ date('Y') == $year ? "selected" : "" }} >{{ $year }}</option>
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
                                    <th>Zapisnik</th>
                                    <th class="no-sort">Akcije</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($msr as $m)
                                <tr>
                                    <td class="text-center">Zapisnik sa preispitivanja {{ $m->year }}</td>
                                    <td class="text-center">
                                        <button class="button text-primary" onclick="showMSR({{ $m->id }})"><i class="fas fa-eye"></i></button>
                                        <a href="{{ route('management-system-reviews.edit', $m->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" action="{{ route('management-system-reviews.destroy', $m->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="button text-danger" type="submit" style="cursor: pointer;" onclick="return confirm('Da li ste sigurni?');"><i class="fas fa-trash"></i></button>
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

    function showMSR(id){
        axios.get('/management-system-reviews/'+id)
            .then((response) => {
                let modal = `<div class="modal" id="showMSR" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content rounded-0">
                                        <div class="modal-header">
                                            <h5 class="modal-title font-weight-bold">Zapisnik sa preispitivanja ${ response.data.year }</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-5 mt-1 border-bottom font-weight-bold"><p>Učestvovali u preispitivanju</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.participants }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Status mera iz prethodnog preispitivanja</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measures_status }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Promene u eksternim i internim pitanjima koje su relevantne za sistem menadžmenta</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.internal_external_changes }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Zadovoljstvo korisnika</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.customer_satisfaction }</p></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Zatvori</button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                $("body").append(modal);
                $('#showMSR').modal();
            })
            .catch((error) => {
                console.log(error)
            })
    }

</script>
