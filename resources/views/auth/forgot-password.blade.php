<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <img src="{{ asset('/images/logo.jpg') }}" class="w-3/5 ml-1/5" alt="imsa-logo">
        </x-slot>
        
  

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Zaboravili ste lozinku? Unesite vašu email adresu i poslaćemo vam link za resetovanje lozinke preko koga ćete moći da izaberete novu lozinku za vaš nalog.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        {{-- <x-jet-validation-errors class="mb-4" />  --}}

        @if($errors->any())
            <div class="text-red-700 text-center"> {{__('Email nije pronađen')}} </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-jet-button>
                    {{ __('Pošalji link za resetovanje lozinke') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
