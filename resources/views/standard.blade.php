<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Standard') }} {{ $standard->name }}
        </h2>
    </x-slot>

    <div class="container my-12 mx-auto px-4 md:px-12">

        <div class="flex flex-wrap -mx-1 lg:-mx-4 mb-4">

            <div class="my-1 mb-2 px-3 w-full md:w-1/2 lg:my-4 lg:px-4 lg:w-1/2">
                <div class="max-w-sm overflow-hidden shadow-lg divide-y divide-gray-400">
                    <div class="px-6 py-4"><div class="font-bold text-xl text-center uppercase">{{ __('Dokumentacija') }}</div></div>
                    <div class="px-6 py-4">
                        <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/rules-of-procedures') }}">{{ __('Poslovnik') }}</a></div>
                        <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/policies') }}">{{ __('Politike') }}</a></div>
                        <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/procedures') }}">{{ __('Procedure') }}</a></div>
                        <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/manuals') }}">{{ __('Uputstva') }}</a></div>
                        <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/forms') }}">{{ __('Obrasci') }}</a></div>
                    </div>
                </div>
            </div>

            @inject('system_processes', 'system_processes')
            <div class="my-1 px-3 w-full md:w-1/2 lg:my-4 lg:px-4 lg:w-1/2">
                <div class="max-w-sm overflow-hidden shadow-lg divide-y divide-gray-400">
                    <div class="px-6 py-4"><div class="font-bold text-xl text-center uppercase">{{ __('Sistemski procesi') }}</div></div>
                    <div class="px-6 py-4">
                        @foreach($system_processes as $sp)
                            <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset($sp->route) }}">{{ __($sp->name) }}</a></div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>{{$system_processes}}

    </div>

</x-app-layout>
