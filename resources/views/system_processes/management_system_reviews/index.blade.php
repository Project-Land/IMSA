<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Zapisnici sa preispitivanja') }}
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
                            @can('create', App\Models\ManagementSystemReview::class)
                            <a class="btn btn-info" href="{{ route('management-system-reviews.create') }}"><i class="fas fa-plus"></i> Kreiraj zapisnik</a>
                            @endcan
                        </div>
                        <div class="col-sm-8">
                            <form class="form-inline">
                                <label for="year" class="mr-3">Godina</label>
                                <select name="year" id="reviews-year" class="form-control w-25 mr-2">
                                    @foreach(range(date('Y')-1, date('Y')+10) as $year)
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
                            <tbody id="table-body">
                                @foreach($msr as $m)
                                <tr>
                                    <td class="text-center">Zapisnik sa preispitivanja {{ $m->year }}</td>
                                    <td class="text-center">
                                        <button class="button text-primary" onclick="showMSR({{ $m->id }})"><i class="fas fa-eye"></i></button>
                                        @canany(['update', 'delete'], $m)
                                        <a href="{{ route('management-system-reviews.edit', $m->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" action="{{ route('management-system-reviews.destroy', $m->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="button text-danger" type="submit" style="cursor: pointer;" onclick="return confirm('Da li ste sigurni?');"><i class="fas fa-trash"></i></button>
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
                let modal = `<div class="modal" id="showMSR-${ id }" tabindex="-1" role="dialog">
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
                                                <div class="col-sm-7 mt-1 border-bottom"><p>${ response.data.participants }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Status mera iz prethodnog preispitivanja</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measures_status }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Promene u eksternim i internim pitanjima koje su relevantne za sistem menadžmenta</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.internal_external_changes }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Zadovoljstvo korisnika</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.customer_satisfaction }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Obim ispunjenosti ciljeva</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.objectives_scope }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Neusaglašenosti i korektivne mere</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.inconsistancies_corrective_measures }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Rezultati praćenja i merenja</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.monitoring_measurement_results }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Rezultati internih provera</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.checks_results }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Rezultati internih provera - dodatne informacije</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.checks_results_desc ? response.data.checks_results_desc : "/" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Performanske eksternih isporučilaca</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.external_suppliers_performance }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Adekvatnost resursa</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.resource_adequacy }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Efektivnost mera koje se odnose na rizike i prilike</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measures_effectiveness }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Prilike za poboljšanje</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.improvement_opportunities ? response.data.improvement_opportunities : "/" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Potrebe za izmenama u sistemu menadžmenta</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.needs_for_change ? response.data.needs_for_change : "/" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Potrebe za resursima</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.needs_for_resources ? response.data.needs_for_resources : "/" }</p></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Zatvori</button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
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
        
        if(confirm('Da li ste sigurni?')){
            $.ajax({
                type: "delete",
                url: url,
                data: { 
                    id: id
                },
                success: function(result) {
                    alert('Zapisnik je uspešno uklonjen');
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
                $('#table-body').html('<td colspan="2" class="dataTables_empty" valign="top">Nema podataka</td>');
            }
            else{
                let allData = "";
                $.each(response.data, function (i, item){
                    let row = `<tr>
                            <td class="text-center">Zapisnik sa preispitivanja ${ item.year }</td>
                            <td class="text-center">
                                <button class="button text-primary" onclick="showMSR(${ item.id })"><i class="fas fa-eye"></i></button>
                                <span class="${ item.isAdmin === false ? 'd-none' : '' }">
                                    <a href="/management-system-reviews/${ item.id }/edit"><i class="fas fa-edit"></i></a>
                                    <a style="cursor: pointer; color: red;" onclick="deleteSingleReview(${ item.id })" data-id="${ item.id }"><i class="fas fa-trash"></i></a>
                                </span>
                            </td>
                            </tr>`;
                    allData += row;
                });
                $('#table-body').html(allData)
            }
        }, (error) => {
            console.log(error);
        })
    });

</script>
