<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Izjava o primenjivosti') }}
        </h2>
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
                    <div class="row">
                        <div class="col-sm-4">
                            @can('create', App\Models\Soa::class)
                                <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('statement-of-applicability.create') }}"><i class="fas fa-plus"></i> {{ __('Kreiraj izjavu') }}</a>
                                <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('statement-of-applicability.edit', \Auth::user()->currentTeam->id) }}"><i class="fas fa-edit"></i> {{ __('Popuni / Izmeni izjavu') }}</a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body bg-white mt-3">
                    <table class="table table-bordered" x-data="{ open5: true, open6: false }">
                        <thead>
                            <tr class="text-center font-bold">
                                <td class="w-1/6">Naziv</td>
                                <td class="w-2/6">Opis</td>
                                <td class="w-1/6">Status</td>
                                <td class="w-2/6">Komentar</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groups as $group)
                                <div>
                                    <tr class="font-bold">
                                        <td colspan="4" class="cursor-pointer" @click="open{{ $group->id }} = ! open{{ $group->id }}">{{ __($group->name) }} <i class="ml-2 fas" :class="{'fa-chevron-up': open{{ $group->id }}, 'fa-chevron-down': ! open{{ $group->id }} }"></i></td>
                                    </tr>

                                    @foreach($soas as $soa)
                                        @if($soa->soaField->soa_field_group_id == $group->id)
                                            <tr class="text-center" :class="{'': open{{ $group->id }}, 'hidden': ! open{{ $group->id }} }">
                                                <td>{{ __($soa->soaField->name) }}</td>
                                                <td>{{ __($soa->soaField->description) }}</td>
                                                <td>{{ __($soa->status) }}</td>
                                                <td>{{ __($soa->comment) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
