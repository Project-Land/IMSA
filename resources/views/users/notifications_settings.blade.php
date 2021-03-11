<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ __('Podešavanje email notifikacija') }}
        </h2>
    </x-slot>

    <div class="row mt-1">
        <div class="col">
            @if(Session::has('status'))
                <x-alert :type="Session::get('status')[0]" :message="__(Session::get('status')[1])"/>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        <div class="md:grid md:grid-cols-3 md:gap-6">

            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900"> {{ __('Podešavanje email notifikacija') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Izaberite tipove notifikacija koje će stizati na vašu email adresu') }}.
                    </p>
                </div>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-2">

                <form method="POST" action="{{ route('users.notification-settings-save') }}">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-3 bg-white sm:p-6">
                            <x-jet-label for="notification_types" value="{{ __('Tipovi email notifikacija') }}" />
                            <label><input type="checkbox" id="notification_types" name="notification_types[]" value="App\Models\Goal" {{ $selected->contains('notifiable_type', 'App\Models\Goal') ? "checked":"" }}/> {{ __('Ciljevi') }} </label><br>
                            <label><input type="checkbox" id="notification_types" name="notification_types[]" value="App\Models\InternalCheck" {{ $selected->contains('notifiable_type', 'App\Models\InternalCheck') ? "checked":"" }}/> {{ __('Interne provere') }} </label><br>
                            <label><input type="checkbox" id="notification_types" name="notification_types[]" value="App\Models\MeasuringEquipment" {{ $selected->contains('notifiable_type', 'App\Models\MeasuringEquipment') ? "checked":"" }} /> {{ __('Merna oprema') }} </label><br>
                            <label><input type="checkbox" id="notification_types" name="notification_types[]" value="App\Models\Supplier" {{ $selected->contains('notifiable_type', 'App\Models\Supplier') ? "checked":"" }}/> {{ __('Odobreni isporučioci') }} </label><br>
                            <label><input type="checkbox" id="notification_types" name="notification_types[]" value="App\Models\Complaint" {{ $selected->contains('notifiable_type', 'App\Models\Complaint') ? "checked":"" }}/> {{ __('Reklamacije') }} </label><br>
                            <label><input type="checkbox" id="notification_types" name="notification_types[]" value="App\Models\CorrectiveMeasure" {{ $selected->contains('notifiable_type', 'App\Models\CorrectiveMeasure') ? "checked":"" }}/> {{ __('Korektivne mere') }} </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-jet-button class="ml-4 mb-4 mr-4">
                                {{ __('Sačuvaj') }}
                            </x-jet-button>
                        </div>

                    </div>
                </form>
            </div>

        </div>

        <x-jet-section-border />

    </div>

</x-app-layout>
