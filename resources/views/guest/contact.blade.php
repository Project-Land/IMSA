<x-guest-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kontakt') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl min-h-screen mx-auto py-6 px-4 sm:px-6 lg:px-8 mt-3">
        <div class="flex flex-row justify-between flex-wrap sm:mt-10">
            <div class="p-4 w-full sm:w-1/3">
                <div class="flex flex-wrap flex-col bg-gray-100 bg-opacity-75 px-8 py-14 rounded-lg overflow-hidden text-center relative">
                    <i class="w-full fas fa-envelope fa-2x sm:fa-3x text-red-700"></i>
                    <a class="text-center text-lg sm:text-3xl mt-4" href="mailto:office@projectland.rs">office@projectland.rs</a>
                </div>
            </div>

            <div class="p-4 w-full sm:w-1/3">
                <div class="flex flex-wrap flex-col bg-gray-100 bg-opacity-75 px-8 py-14 rounded-lg overflow-hidden text-center relative">
                    <i class="w-full fas fa-phone-alt fa-2x sm:fa-3x text-red-700"></i>
                    <a class="text-center text-lg sm:text-3xl mt-4" href="tel:+38163237284">+38163/237-284</a>
                </div>
            </div>

            <div class="p-4 w-full sm:w-1/3">
                <div class="flex flex-wrap flex-col bg-gray-100 bg-opacity-75 px-8 py-14 rounded-lg overflow-hidden text-center relative">
                    <i class="w-full fas fa-globe fa-2x sm:fa-3x text-red-700"></i>
                    <a class="text-center text-lg sm:text-3xl mt-4" href="http://www.projectland.rs">www.projectland.rs </a>
                </div>
            </div>
        </div>
    </div>

</x-guest-layout>
