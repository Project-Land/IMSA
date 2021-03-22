<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Izjava o primenljivosti') }}
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
                    <div class="">

                            @can('create', App\Models\Soa::class)
                                <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('statement-of-applicability.create') }}"><i class="fas fa-plus"></i> {{ __('Kreiraj izjavu') }}</a>
                            @else
                                <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('statement-of-applicability.edit', \Auth::user()->currentTeam->id) }}"><i class="fas fa-edit"></i> {{ __('Popuni / Izmeni izjavu') }}</a>
                            @endcan


                            <a id="excelBtn" class="inline-block float-right text-xs md:text-base bg-green-500 hover:bg-green-700 text-white hover:no-underline rounded py-2 px-3" href="{{ '/statement-of-applicability-export' }}"><i class="fas fa-file-export"></i> {{ __('Excel') }}</a>

                    </div>
                </div>
                <div class="card-body bg-white mt-3">
                    <table class="table table-bordered" x-data="{ @foreach($groups as $g) open{{ $g->id }}:true, @endforeach }">
                        <thead>
                            <tr class="text-center font-bold">
                                <td class="w-1/6">{{ __('Naziv') }}</td>
                                <td class="w-2/6">{{ __('Opis') }}</td>
                                <td class="w-1/6">{{ __('Status') }}</td>
                                <td class="w-2/6">{{ __('Komentar') }}</td>
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
                                            <tr class="text-center text-sm" :class="{'': open{{ $group->id }}, 'hidden': ! open{{ $group->id }} }">
                                                <td>{{ __($soa->soaField->name) }}</td>
                                                <td>{{ __($soa->soaField->description) }}</td>
                                                <td>{{ ($soa->status !=  null)? __($soa->status) : "/" }}</td>
                                                <td>
                                                    {{ $soa->comment != null ? __($soa->comment) : '/' }}
                                                    @if($soa->documents->count() != 0)
                                                        <p class="font-bold text-sm mt-2 mb-0">{{ __('Relevantna dokumenta') }}:</p>
                                                        @foreach($soa->documents as $doc)
                                                        <form class="inline" action="{{ route('document.preview') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="folder" value="{{ Str::snake(Auth::user()->currentTeam->name).'/'.$doc->doc_category }}">
                                                            <input type="hidden" name="file_name" value="{{ $doc->file_name }}">
                                                            <button data-toggle="tooltip" data-placement="top" title="{{__('Pregled dokumenta')}}" class="button text-primary cursor-pointer text-sm" type="submit" formtarget="_blank">{{ $doc->document_name }}</button>
                                                        </form>
                                                        <br>
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                    <p>{{ __('Kreirao') }}: {{ $soas[0]->user->name ?? "" }}</p>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
