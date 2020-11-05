<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Standard') }} {{ $standard->name }}
        </h2>
    </x-slot>

    {{-- <div class="row mt-3">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <li><a href="/rules-of-procedures">Poslovnik</a></li>
                    <li><a href="/policies">Politike</a></li>
                    <li><a href="/procedures">Procedure</a></li>
                    <li><a href="/manuals">Uputstva</a></li>
                    <li><a href="/forms">Obrasci</a></li>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="container my-12 mx-auto px-4 md:px-12">

    <div class="flex flex-wrap -mx-1 lg:-mx-4 mb-4">

        <div class="my-1 mb-2 px-3 w-full md:w-1/2 lg:my-4 lg:px-4 lg:w-1/2">
            <div class="max-w-sm overflow-hidden shadow-lg divide-y divide-gray-400">
                <div class="px-6 py-4"><div class="font-bold text-xl text-center">DOKUMENTACIJA</div></div>
                <div class="px-6 py-4">
                    <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/rules-of-procedures') }}">Poslovnik</a></div>
                    <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/policies') }}">Politike</a></div>
                    <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/procedures') }}">Procedure</a></div>
                    <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/manuals') }}">Uputstva</a></div>
                    <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/forms') }}">Obrasci</a></div>
                </div>
            </div>
        </div>

        <div class="my-1 px-3 w-full md:w-1/2 lg:my-4 lg:px-4 lg:w-1/2">
            <div class="max-w-sm overflow-hidden shadow-lg divide-y divide-gray-400">
                <div class="px-6 py-4"><div class="font-bold text-xl text-center">SISTEMSKI PROCESI</div></div>
                <div class="px-6 py-4">
                    <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/risk-management') }}">Upravljanje rizikom</a></div>
                    <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/internal-check') }}">Interne provere</a></div>
                    <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/corrective-measures') }}">Neusaglašenosti i korektivne mere</a></div>
                    <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/trainings') }}">Obuke</a></div>
                    <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/goals') }}">Ciljevi</a></div>
                    <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/suppliers') }}">Odabrani isporučioci</a></div>
                    <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/stakeholders') }}">Zainteresovane strane</a></div>
                    <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/complaints') }}">Upravljanje reklamacijama</a></div>
                    <div class="font-bold xl:text-lg sm:text-sm mb-2"><a class="hover:no-underline" href="{{ asset('/management-system-reviews') }}">Preispitivanje sistema menadžmenta</a></div>
                </div>
            </div>
        </div>

    </div>

    </div>  

</x-app-layout>
