<x-app-layout>
    @push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"
        integrity="sha512-UdIMMlVx0HEynClOIFSyOrPggomfhBKJE28LKl8yR3ghkgugPnG6iLfRfHwushZl1MOPSY6TsuBDGPK2X4zYKg=="
        crossorigin="anonymous"></script>
    @endpush

    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
                {{ session('standard_name') }} - {{ __('Plan za postupanje sa rizikom / prilikom') }} -
                {{ __('Kreiranje / izmena plana') }}
            </h2>
            @include('includes.video')
        </div>
    </x-slot>

    <div class="row">
        <div class="col">
            <a class="btn btn-light" href="{{ route('risk-management.index') }}"><i class="fas fa-arrow-left"></i>
                {{ __('Nazad') }}</a>
        </div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

        <div class="row">
            <div class="col">
                <p class="font-bold bg-white text-center text-lg shadow-md rounded px-8 pt-2 pb-2">{{ $risk->measure }}
                    - {{ date('d.m.Y', strtotime($risk->measure_created_at)) }}</p>
            </div>
        </div>

        <form id="edit-plan" action="{{ route('risk-management.update-plan', $risk->id) }}" method="POST"
            autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="cause" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Uzrok') }}:</label>
                <input type="text"
                    class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="cause" name="cause" value="{{ $risk->cause }}" autofocus required>
                @error('cause')
                <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="risk_lowering_measure"
                    class="block text-gray-700 text-sm font-bold mb-2">{{ __('Mera za smanjenje rizika/ korišćenje prilike') }}:</label>
                <input type="text"
                    class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="risk_lowering_measure" name="risk_lowering_measure" value="{{ $risk->risk_lowering_measure }}"
                    required>
                @error('risk_lowering_measure')
                <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="responsibility"
                    class="block text-gray-700 text-sm font-bold mb-2">{{ __('Odgovornost') }}</label>
                <select
                    class="js-example-basic-multiple block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    id="responsibility" name="responsibility[]" multiple required>
                    @foreach($users as $user)
                    @if($user->teams[0]->membership->role != 'super-admin')
                    <option value="{{ $user->id }}" {{ $risk->users->contains($user->id) ? "selected":"" }}>
                        {{ $user->name }}</option>
                    @endif
                    @endforeach
                </select>
                @error('responsibility')
                <span class="text-red-700 italic text-sm">{{ __($message) }}</span><br>
                @enderror
            </div>

            <div class="mb-4">
                <label for="deadline"
                    class="block text-gray-700 text-sm font-bold mb-2">{{ __('Rok za realizaciju') }}:</label>
                <input type="text"
                    class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="deadline" name="deadline"
                    value="{{ $risk->deadline != null ? date('d.m.Y', strtotime($risk->deadline)) : date('d.m.Y') }}"
                    required oninvalid="this.setCustomValidity('{{ __("Izaberite datum") }}')"
                    oninput="this.setCustomValidity('')" onchange="this.setCustomValidity('')">
                @error('deadline')
                <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Status') }}:</label>
                <select
                    class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    name="status" id="status">
                    <option value="1" {{ ($risk->status === 1 || $risk->status == null)? "selected" : "" }}>
                        {{ __('Otvorena') }}</option>
                    <option value="0" {{ ($risk->status === 0)? "selected" : "" }}>{{ __('Zatvorena') }}</option>
                </select>
            </div>

            <button type="submit"
                class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{ __('Sačuvaj') }}</button>
        </form>
    </div>

    <style>
        .select2-results {
            font-size: 0.875rem;
        }

        .select2-container--default .select2-selection--multiple {
            border-radius: 0;
            padding-top: 5px;
            padding-bottom: 10px;
            border: 1px solid #dee2e6 !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: 1px solid #dee2e6 !important;
        }

        .select2-selection__choice {
            font-size: 0.875rem;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            border-radius: 1px;
        }
    </style>

    @push('page-scripts')
    <script>
        $("#edit-plan").validate({
                messages: {
                    cause: "{{ __('Popunite polje') }}",
                    risk_lowering_measure: "{{ __('Popunite polje') }}",
                    responsibility: "{{ __('Popunite polje') }}",
                }
            });

            var lang = document.getElementsByTagName('html')[0].getAttribute('lang');
            $.datetimepicker.setLocale(lang);

            $('.js-example-basic-multiple').select2();

            $('#deadline').datetimepicker({
                timepicker: false,
                format:'d.m.Y',
                minDate: 0,
                dayOfWeekStart: 1,
                scrollInput: false
            });
    </script>
    @endpush

</x-app-layout>
