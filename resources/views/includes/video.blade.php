<div>
    <i class="fas fa-video cursor-pointer text-2xl text-green-400" id="on" data-toggle="tooltip" data-placement="top" title="{{__('Video pomoć')}}" ></i>
</div>
<div id="video" class="w-full h-1/3 md:w-1/2 md:h-2/5 lg:w-1/3 fixed top-0 right-0 z-50 d-none">
    <div class="video-header bg-black cursor-move py-1 border flex flex-row justify-between text-white">
        <span class="font-semibold uppercase pl-2 pt-1">{{ __('Kliknite ovde za pomeranje prozora') }}</span>
        <button id="off" class="text-red-500 pr-3"><i class="fas fa-times fa-2x"></i></button>
    </div>
    <iframe id="youtube" class="w-full h-full" src="{{'https://www.youtube-nocookie.com/embed/'.$help_video.'?rel=0'}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope;" allowfullscreen></iframe>
</div>
