<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Rizici i prilike') }}
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
                    <a class="btn btn-info" href="{{ route('risk-management.create') }}"><i class="fas fa-plus"></i> Kreiraj rizik / priliku</a>
                </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>Opis rizika / prilike</th>
                                    <th>Verovatnoća</th>
                                    <th>Učestalost</th>
                                    <th>Ukupno</th>
                                    <th>Prihvatljivo</th>
                                    <th>Mera</th>
                                    <th class="no-sort">Akcije</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riskManagements as $risk)
                                <tr>
                                    <td class="text-center">{{ $risk->description }}</td>
                                    <td class="text-center">{{ $risk->probability }}</td>
                                    <td class="text-center">{{ $risk->frequency }}</td>
                                    <td class="text-center">{{ $risk->total }}</td>
                                    <td class="text-center">{{ $risk->acceptable }}</td>
                                    <td class="text-center"><span @if($risk->measure) style="cursor: pointer; color: blue;"  onclick="showMeasure({{ $risk->id }})" @endif >{{ ($risk->measure) ? : '/' }}</span>
                                        @if($risk->measure)
                                            <a href="{{ route('risk-management.edit-plan', $risk->id) }}"><i class="fas fa-pen"></i></a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('risk-management.edit', $risk->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" action="{{ route('risk-management.destroy', $risk->id) }}" method="POST">
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

    function showMeasure(id){
        axios.get('/risk-management/'+id)
            .then((response) => {
                let modal = `<div class="modal" id="showMeasure-${ id }" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content rounded-0">
                                        <div class="modal-header">
                                            <h5 class="modal-title font-weight-bold">${ response.data.measure }</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-5 mt-1 border-bottom font-weight-bold"><p>Opis rizika / prilike</p></div>
                                                <div class="col-sm-7 mt-1 border-bottom"><p>${ response.data.description }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Uzrok</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.cause }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Mera za smanjenje rizika</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.risk_lowering_measure }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Odgovornost</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.responsibility }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Rok za realizaciju</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ new Date(response.data.deadline).toLocaleDateString('sr-SR', { timeZone: 'CET' }) }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Status</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.status == 0 ? "Zatvorena" : "Otvorena" }</p></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Zatvori</button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                $("body").append(modal);
                $('#showMeasure-'+id).modal();
            })
            .catch((error) => {
                console.log(error)
            })
    }
</script>
