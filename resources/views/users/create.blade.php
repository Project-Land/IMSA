<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ __('Kreiranje novog korisnika') }}
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
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Kreiraj novog korisnika') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Kreirajte novi nalog za korisnike vaše aplikacije') }}
                    </p>
                </div>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-2">

                <form method="POST" action="{{ route('users.store') }}" autocomplete="off">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">

                        <div class="px-4 py-3 bg-white sm:p-6">
                            <x-jet-label for="name" value="{{ __('Ime') }}" class="block font-medium text-sm text-gray-700" />
                            <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" autofocus autocomplete="name" />
                            @error('name')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-3 bg-white sm:p-6">
                            <x-jet-label for="username" value="{{ __('Korisničko ime') }}" class="block font-medium text-sm text-gray-700" />
                            <x-jet-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" />
                            @error('username')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-3 bg-white sm:p-6">
                            <x-jet-label for="email" value="{{ __('Email') }}" class="block font-medium text-sm text-gray-700" />
                            <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
                            @error('email')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                            <span class="text-xs text-gray-500">{{ __('Polje nije obavezno') }}</span>
                        </div>

                        <div class="px-4 py-3 bg-white sm:p-6">
                            <x-jet-label for="role" value="{{ __('Uloga') }}" class="block font-medium text-sm text-gray-700" />
                            <select class="block mt-1 appearance-none w-full border border-gray-700 font-small text-sm text-gray-700 py-2 px-2 pr-8 rounded-md shadow-sm focus:outline-none focus:bg-white focus:border-gray-500" name="role" id="role">
                                <option value="user" {{ old('role') == "user" ? "selected":"" }}>{{ __('Korisnik') }}</option>
                                <option value="editor" {{ old('role') == "editor" ? "selected":"" }}>{{ __('Menadžer') }}</option>
                                <option value="admin" {{ old('role') == "admin" ? "selected":"" }}>{{ __('Administrator') }}</option>
                            </select>
                            @error('role')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="certificates-block" class="px-4 py-3 bg-white sm:p-6">
                            <x-jet-label for="certificates" value="{{ __('Dozvole pristupa') }}" class="block font-medium text-sm text-gray-700" />

                            @foreach($certificates as $certificate)
                                <label class="inline-flex items-center mt-3 cursor-pointer mr-2 {{ $certificate->name != 'editor'? 'certificate':'' }}">
                                    <input type="checkbox" name="certificates[]" value="{{ $certificate->id }}" class="form-checkbox h-5 w-5 text-gray-600"><span class="ml-2 text-gray-700 text-sm">{{ __($certificate->display_name) }}</span>
                                </label>
                            @endforeach

                            @error('certificates')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-3 bg-white sm:p-6">
                            <x-jet-label for="password" value="{{ __('Lozinka') }}" class="block font-medium text-sm text-gray-700" />
                            <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                            @error('password')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-3 bg-white sm:p-6">
                            <x-jet-label for="password_confirmation" value="{{ __('Potvrdi lozinku') }}" class="block font-medium text-sm text-gray-700" />
                            <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                            @error('password_confirmation')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-jet-button class="ml-4 mb-4 mr-4">
                                {{ __('Kreiraj') }}
                            </x-jet-button>
                        </div>

                    </div>
                </form>
            </div>

        </div>

        <x-jet-section-border />

    </div>

</x-app-layout>

<script>
    $('#role').change(() => {
        if($('#role').val() == 'user'){
            $('input[name="certificates[]"]').each(function() {
                this.checked = false;
            });
            $('#certificates-block').addClass('d-none')
        }
        if($('#role').val() == 'admin'){
            $('.certificate').addClass('d-none')
        }
        else{
            $('#certificates-block').removeClass('d-none')
            $('.certificate').removeClass('d-none')
        }
    });
</script>
