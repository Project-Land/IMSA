<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Odobreni isporučioci') }}
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
            @if(Session::has('warning'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ Session::get('warning') }}
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
                @can('create', App\Models\Supplier::class)
                <div class="card-header">
                    <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('suppliers.create') }}"><i class="fas fa-plus"></i> Kreiraj isporučioca</a>
                </div>
                @endcan
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>Naziv isporučioca</th>
                                    <th>Predmet nabavke</th>
                                    <th>Status</th>
                                    <th>Datum kreiranja</th>
                                    <th>Datum sledećeg preispitivanja</th>
                                    <th class="no-sort">Akcije</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliers as $s)
                                <tr id="trsupplier{{$s->id}}"><a id="supplier{{$s->id}}"></a>
                                    <td id='tdsupplier{{$s->id}}' class="text-center">{{ $s->supplier_name }}</td>
                                    <td class="text-center">{{ $s->subject }}</td>
                                    <td class="text-center">{{ ($s->status == '1') ? 'Odobren' : 'Neodobren' }}</td>
                                    <td class="text-center">{{ date('d.m.Y', strtotime($s->created_at)) }}</td>
                                    <td class="text-center">{{ date('d.m.Y', strtotime($s->deadline_date)) }}</td>
                                    <td class="text-center">
                                        <button data-toggle="tooltip" data-placement="top" title="Pregled isporučioca" class="button text-primary" onclick="showSupplier({{ $s->id }})"><i class="fas fa-eye"></i></button>
                                        @canany(['update', 'delete'], $s)
                                            <a data-toggle="tooltip" data-placement="top" title="Izmena isporučioca" href="{{ route('suppliers.edit', $s->id) }}"><i class="fas fa-edit"></i></a>
                                            <form class="inline" id="delete-form-{{ $s->id }}" action="{{ route('suppliers.destroy', $s->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button onclick="confirmDeleteModal({{ $s->id }})" class="text-red-600 cursor-pointer hover:text-red-800" type="button" data-toggle="tooltip" data-placement="top" title="Brisanje isporučioca"><i class="fas fa-trash"></i></button>
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
                    <div class="text-lg">Brisanje isporučioca</div>
                    <div class="mt-4">Da li ste sigurni?</div>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">Odustani</button>
                    <a class="btn-ok hover:no-underline cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150 ml-2">Obriši</a>
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
        "order": [[ 4, "desc" ]]
    });

    var myRe = /\bsuppliers\b/g;
    if(myRe.test(window.location.href)){
        window.addEventListener('popstate', function (event) {
            location.reload();
        });
    }

    let href = window.location.href;
    id = href.split('#')[1];
    if(id){
        let e= document.getElementById('tr'+id);
        let i = document.getElementById('td' + id);
        i.innerHTML='<i class="fas fa-hand-point-right"></i> ' +  i.innerHTML;
        e.style="background:#d8ffcc;";
    }

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

    function showSupplier(id){
        axios.get('/suppliers/'+id)
            .then((response) => {
                let modal = `<div class="modal" id="showSupplierModal-${ id }" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content rounded-0">
                                        <div class="modal-header">
                                            <h5 class="modal-title font-weight-bold">${ response.data.supplier_name }</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row text-sm">
                                                <div class="col-sm-5 mt-1 border-bottom font-weight-bold text-sm"><p>Datum kreiranja</p></div>
                                                <div class="col-sm-7 mt-1 border-bottom"><p>${ new Date(response.data.created_at).toLocaleDateString('sr-SR', { timeZone: 'CET' }) }</p></div>
                                                <div class="col-sm-5 mt-1 border-bottom font-weight-bold text-sm"><p>Naziv isporučioca</p></div>
                                                <div class="col-sm-7 mt-1 border-bottom"><p>${ response.data.supplier_name }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>Predmet nabavke</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.subject }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>Ime i prezime kontakt osobe kod isporučioca</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.personal_info != null ? response.data.personal_info : '/' }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>Broj telefona kontakt osobe kod isporučioca</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.phone_number != null ? response.data.phone_number : '/' }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>Email kontakt osobe kod isporučioca</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.email != null ? response.data.email : '/' }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>Kvalitet</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.quality }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>Cena</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.price }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>Rok isporuke</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.shippment_deadline }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold text-sm"><p>Status</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.status === 1 ? 'Odobren' : 'Neodobren' }</p></div>
                                                <div class="col-sm-5 mt-1 border-bottom font-weight-bold text-sm"><p>Datum sledećeg preispitivanja</p></div>
                                                <div class="col-sm-7 mt-1 border-bottom"><p>${ new Date(response.data.deadline_date).toLocaleDateString('sr-SR', { timeZone: 'CET' }) }</p></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Zatvori</button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                $("body").append(modal);
                $('#showSupplierModal-'+id).modal();
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
