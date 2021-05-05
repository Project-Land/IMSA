
<nav id="footer" class="bg-gray-500" >
    <div class="container mx-auto pt-4 pb-2">
        <div class="pt-1 flex items-center justify-center">
            <ul>
                <li class="mx-2 inline leading-7 text-sm"><a class="text-white text-small" href="{{ route('manual') }}" target="_blank">{{ __('Uputstvo za korišćenje') }}</a></li>
                <li class="mx-2 inline leading-7 text-sm"><a class="text-white text-small" href="{{ route('about') }}">{{ __('O aplikaciji') }}</a></li>
                <li class="mx-2 inline leading-7 text-sm"><a class="text-white text-small" href="{{ route('contact') }}">{{ __('Kontakt') }}</a></li>
            </ul>
            <br>
        </div>
        <div class="pt-1 flex items-center justify-center"><p class="text-white">IMSA © Copyright {{ date('Y') }}</p></div>
    </div>
</nav>
