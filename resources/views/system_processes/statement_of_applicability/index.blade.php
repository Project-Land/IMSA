<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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
                    <div class="row">
                        <div class="col-sm-4">
                            @can('create', App\Models\Training::class)
                                <a class="inline-block text-xs md:text-base bg-blue-500 hover:bg-blue-700 text-white hover:no-underline rounded py-2 px-3" href="{{ route('statement-of-applicability.edit', \Auth::user()->currentTeam->id) }}"><i class="fas fa-edit"></i> {{ __('Popuni / Izmeni izjavu') }}</a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body bg-white mt-3">
                    <table class="table table-bordered" x-data="{ open5: true, open6: false }">
                        <thead>
                            <tr class="text-center font-bold">
                                <td>Naziv</td>
                                <td>Opis</td>
                                <td>Status</td>
                                <td>Komentar</td>
                            </tr>
                        </thead>
                        <tbody>
                            <div>
                                <tr class="font-bold">
                                    <td colspan="4" class="cursor-pointer" @click="open5 = ! open5">Politike bezbednosti informacija <i class="ml-2 fas" :class="{'fa-chevron-up': open5, 'fa-chevron-down': ! open5 }"></i></td>
                                </tr>
                                <tr class="text-center" :class="{'': open5, 'hidden': ! open5 }">
                                    <td>Politike bezbednosti informacija</td>
                                    <td>Rukovodstvo mora da definiše i odobri skup politika bezbednosti informacija, da ih objavi i saopšti svim zaposlenima i odgovarajućim eksternim stranama.</td>
                                    <td>Prihvaćen</td>
                                    <td>Test comment</td>
                                </tr>
                            </div>
                            <div>
                                <tr class="font-bold">
                                    <td colspan="4" class="cursor-pointer" @click="open6 = ! open6">Organizovanje bezbednosti informacija <i class="ml-2 fas" :class="{'fa-chevron-up': open6, 'fa-chevron-down': ! open6 }"></i></td>
                                </tr>
                                <tr class="text-center" :class="{'': open6, 'hidden': ! open6 }">
                                    <td>Politike bezbednosti informacija</td>
                                    <td>Rukovodstvo mora da definiše i odobri skup politika bezbednosti informacija, da ih objavi i saopšti svim zaposlenima i odgovarajućim eksternim stranama.</td>
                                    <td>Prihvaćen</td>
                                    <td>Test comment</td>
                                </tr>
                            </div>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
