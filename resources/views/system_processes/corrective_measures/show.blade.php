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
                    <p>{{ $corrective_measure->name }}</p>
                </div>

                <div class="card-body bg-white mt-3">
                    <div class="row">
                        <div class="col-md-4">
                            <p>Br. kartona</p>
                            <p>Datum kreiranja</p>
                            <p>Sistem menadžment</p>
                            <p>Opis neusaglašenosti</p>
                            <p>Uzrok neusaglašenosti</p>
                            <p>Mera za otklanjanje neusaglašenosti</p>
                            <p>Mera odobrena</p>
                            @unless($corrective_measure->measure_approval_reason == null)<p>Razlog neodobravanja mere</p>@endunless
                            @unless($corrective_measure->measure_approval_date == null)<p>Datum odobravanja mere</p>@endunless
                            <p>Status mere</p>
                            @unless($corrective_measure->measure_effective == null)<p>Mera efektivna</p>@endunless
                        </div>
                        <div class="col-md-8">
                            <p>{{ $corrective_measure->name }}</p>
                            <p>{{ date('d.m.Y', strtotime($corrective_measure->created_at)) }}</p>
                            <p>{{ $corrective_measure->standard->name }}</p>
                            <p>{{ $corrective_measure->noncompliance_description }}</p>
                            <p>{{ $corrective_measure->noncompliance_cause }}</p>
                            <p>{{ $corrective_measure->measure }}</p>
                            <p>{{ $corrective_measure->measure_approval == 1 ? "Odobrena" : "Neodobrena" }}</p>
                            @unless($corrective_measure->measure_approval_reason == null)<p>{{ $corrective_measure->measure_approval_reason }}</p>@endunless
                            @unless($corrective_measure->measure_approval_date == null)<p>{{ date('d.m.Y', strtotime($corrective_measure->measure_approval_date)) }}</p>@endunless
                            <p>{{ $corrective_measure->measure_status == 1 ? "Da" : "Ne" }}</p>    
                            @unless($corrective_measure->measure_effective == null)<p>{{ $corrective_measure->measure_effective == 1 ? "Efektivna" : "Neefektivna" }}</p>@endunless
                        </div>
                    </div>
                </div>
            </div>  

        </div>

    </div>

</x-app-layout>
