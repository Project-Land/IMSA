<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Standard') }} {{session('standard_name')}}
           
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
                    <a class="btn btn-info" href="{{ route('internal-check.create') }}"><i class="fas fa-plus"></i> Kreiraj novi godišnji plan</a>
                    
                </div>
                <div class="card-body bg-white mt-3">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr class="text-center">
                                    <th>Termin provere</th>
                                    <th>Područije provere</th>
                                    <th>Vođe tima i proveravači</th>
                                    <th>Standard</th>
                                    <th>Br program ip</th>
                                    <th>Izveštaji sa internih provera</th>
                                    <th class="no-sort">Akcije</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($internal_checks as $check)
                                <tr>
                                    <td>{{ $check->date }}</td>
                                    <td>{{ $check->sector }}</td>
                                    <td>{{ $check->leaders }}</td>
                                    <td>{{ $check->standard->name }}</td>
                                    <td class="text-center">
                                    @if(!isset($check->planIp->checked_date))
                                       {{''}}
                                      
                                    <a href="{{ route('plan-ip.edit',$check->planIp->id) }}"><i class="fas fa-plus"></i></a>
                                    @else
                                    {{'PIP'}}  {{$check->planIp->name}}
                                    <a href="{{ route('plan-ip.edit', $check->planIp->id) }}"><i class="fas fa-edit"></i></a>
                                    <form class="inline" action="{{ route('plan-ip.destroy', $check->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="button" type="submit" style="cursor: pointer;" onclick="return confirm('Da li ste sigurni?');"><i class="fas fa-trash"></i></button>
                                    </form>
                                    @endif
                                    
                            
                                    </td>

                                    <td class="text-center">

                                    @if(!isset($check->internalCheckReport->id))
                                    @if(isset($check->planIp->checked_date))
                                    <a href="{{ route('create.report', $check->id) }}"><i class="fas fa-plus"></i></a>
                                    @else
                                    {{'Još nije moguće napraviti izveštaj'}}
                                    @endif
                                      
                                   
                                    @else
                                    <a href="{{ route('internal-check-report.edit', $check->internalCheckReport->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" action="{{ route('internal-check-report.destroy', $check->internalCheckReport->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="button" type="submit" style="cursor: pointer;" onclick="return confirm('Da li ste sigurni?');"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                    @endif
                                    
                                       
                                    <td class="text-center">
                                       
                                        <a href="{{ route('internal-check.edit', $check->id) }}"><i class="fas fa-edit"></i></a>
                                        <form class="inline" action="{{ route('internal-check.destroy', $check->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="button" type="submit" style="cursor: pointer;" onclick="return confirm('Da li ste sigurni?');"><i class="fas fa-trash"></i></button>
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
</script>