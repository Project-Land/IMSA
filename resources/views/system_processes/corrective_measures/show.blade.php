<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Neusaglašenosti i korektivne mere') }} - {{ $corrective_measure->name }}
        </h2>
    </x-slot>

    <div class="row">
        <div class="col">
            <a class="btn btn-light" href="{{ route('corrective-measures.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
        </div>
    </div>

    <div class="row mt-3">

        <div class="col">

            <div class="card">

                <div class="card-header">
                    <h5 class="text-center mt-2 font-weight-bold">{{ $corrective_measure->name }}</h5>
                </div>

                <div class="card-body bg-white mt-3">
                    <div class="row">
                        <div class="col-sm-3 mt-3 border-bottom font-weight-bold"><p>Br. kartona</p></div>
                        <div class="col-sm-9 mt-3 border-bottom"><p>{{ $corrective_measure->name }}</p></div>
                        <div class="col-sm-3 mt-3 border-bottom font-weight-bold""><p>Datum kreiranja</p></div>
                        <div class="col-sm-9 mt-3 border-bottom"><p>{{ date('d.m.Y', strtotime($corrective_measure->created_at)) }}</p></div>
                        <div class="col-sm-3 mt-3 border-bottom font-weight-bold""><p>Sistem menadžment</p></div>
                        <div class="col-sm-9 mt-3 border-bottom"><p>{{ $corrective_measure->standard->name }}</p></div>
                        <div class="col-sm-3 mt-3 border-bottom font-weight-bold""><p>Opis neusaglašenosti</p></div>
                        <div class="col-sm-9 mt-3 border-bottom"><p>{{ $corrective_measure->noncompliance_description }}</p></div>
                        <div class="col-sm-3 mt-3 border-bottom font-weight-bold""><p>Uzrok neusaglašenosti</p></div>
                        <div class="col-sm-9 mt-3 border-bottom"><p>{{ $corrective_measure->noncompliance_cause }}</p></div>
                        <div class="col-sm-3 mt-3 border-bottom font-weight-bold""><p>Mera za otklanjanje neusaglašenosti</p></div>
                        <div class="col-sm-9 mt-3 border-bottom"><p>{{ $corrective_measure->measure }}</p></div>
                        <div class="col-sm-3 mt-3 border-bottom font-weight-bold""><p>Mera odobrena</p></div>
                        <div class="col-sm-9 mt-3 border-bottom"><p>{{ $corrective_measure->measure_approval == 1 ? "Odobrena" : "Neodobrena" }}</p></div>
                        @unless($corrective_measure->measure_approval_reason == null)
                        <div class="col-sm-3 mt-3 border-bottom font-weight-bold""><p>Razlog neodobravanja mere</p></div>
                        <div class="col-sm-9 mt-3 border-bottom"><p>{{ $corrective_measure->measure_approval_reason }}</p></div>
                        @endunless
                        @unless($corrective_measure->measure_approval_date == null)
                        <div class="col-sm-3 mt-3 border-bottom font-weight-bold""><p>Datum odobravanja mere</p></div>
                        <div class="col-sm-9 mt-3 border-bottom"><p>{{ date('d.m.Y', strtotime($corrective_measure->measure_approval_date)) }}</p></div>
                        @endunless
                        <div class="col-sm-3 mt-3 border-bottom font-weight-bold""><p>Status mere</p></div>
                        <div class="col-sm-9 mt-3 border-bottom"><p>{{ $corrective_measure->measure_status == 1 ? "Da" : "Ne" }}</p> </div>
                        @unless($corrective_measure->measure_effective == null)
                        <div class="col-sm-3 mt-3 border-bottom font-weight-bold""><p>Mera efektivna</p></div>
                        <div class="col-sm-9 mt-3 border-bottom"><p>{{ $corrective_measure->measure_effective == 1 ? "Efektivna" : "Neefektivna" }}</p></div>
                        @endunless
                    </div>
                </div>
            </div>  

        </div>

    </div>

</x-app-layout>
