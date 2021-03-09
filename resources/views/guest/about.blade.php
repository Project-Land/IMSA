<x-guest-layout>

    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('O aplikaciji') }}
            </h2>
        </div>
    </header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 mt-3">

        <p class="w-75 sm:text-lg">
        {{ __('Aplikacija podrazumeva efikasno i pouzdano rešenje koje je jednostavno za upotrebu i predstavlja neophodan korak ka unapređenju sistema menadžmenta.Predstavlja pouzdan alat za otklanjanje nepotrebnih gubitaka koji se javljaju zbog spore distribucije dokumenata, nepotrebnih troškova štampanja dokumenata, neprecizne kontrole pristupa dokumentima, česte upotrebe nevalidne verzije dokumentacije, nepravovremenog ili neadekvatnog obaveštavanja zaposlenih o svim izmenama u sistemu, i dr.') }}
        </p>

        <p class="sm:text-lg mt-3 mb-1">{{ __('Osim opštih, specifični benefiti primene aplikacije su:') }}</p>

        <ul class="ml-5 sm:text-lg">
            <li class="pb-1"><i class="fas fa-check mr-1"></i> {{ __('Omogućava centralizovano i sistemsko praćenje ciljeva, kao i stepena njihove realizacije') }}</li>
            <li class="pb-1"><i class="fas fa-check mr-1"></i> {{ __('Obezbeđuje sistemsko upravljanje rizicima') }}</li>
            <li class="pb-1"><i class="fas fa-check mr-1"></i> {{ __('Olakšava praćenje performansi i ocenjivanje isporučilaca') }}</li>
            <li class="pb-1"><i class="fas fa-check mr-1"></i> {{ __('Značajno ubrzava i pojednostavljuje proces preispitivanja od strane rukovodstva') }}</li>
            <li class="pb-1"><i class="fas fa-check mr-1"></i> {{ __('Olakšava proces upravljanja reklamacijama') }}</li>
            <li class="pb-1"><i class="fas fa-check mr-1"></i> {{ __('Olakšava planiranje i organizovanje internih provera') }}</li>
            <li class="pb-1"><i class="fas fa-check mr-1"></i> {{ __('Obezbeđuje ažurnost korišćene dokumentacije') }}</li>
            <li class="pb-1"><i class="fas fa-check mr-1"></i> {{ __('Smanjuje rizik od gubljenja/suspenzije sertifikata') }}</li>
            <li class="pb-1"><i class="fas fa-check mr-1"></i> {{ __('Obezbeđuje jasno i pregledno praćenje potreba i zahteva zainteresovanih strana') }}</li>
        </ul>

        <div class="slideshow-container">
            <div class="text-center">
                <span class="dot" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
                <span class="dot" onclick="currentSlide(3)"></span>
                <span class="dot" onclick="currentSlide(4)"></span>
                <span class="dot" onclick="currentSlide(5)"></span>
                <span class="dot" onclick="currentSlide(6)"></span>
                <span class="dot" onclick="currentSlide(7)"></span>
            </div>
            <!-- Full-width images with number and caption text -->
            <div class="mySlides fade">
                <div class="numbertext">1 / 7</div>
                <img src="{{asset('images/1.JPG')}}" style="width:100%">
                <div class="text">IMSA</div>
            </div>
            <div class="mySlides fade">
                <div class="numbertext">2 / 7</div>
                <img src="{{asset('images/2.JPG')}}" style="width:100%">
                <div class="text">IMSA</div>
            </div>
            <div class="mySlides fade">
                <div class="numbertext">3 / 7</div>
                <img src="{{asset('images/3.JPG')}}" style="width:100%">
                <div class="text">IMSA</div>
            </div>
            <div class="mySlides fade">
                <div class="numbertext">4 / 7</div>
                <img src="{{asset('images/4.JPG')}}" style="width:100%">
                <div class="text">IMSA</div>
            </div>
            <div class="mySlides fade">
                <div class="numbertext">5 / 7</div>
                <img src="{{asset('images/5.JPG')}}" style="width:100%">
                <div class="text">IMSA</div>
            </div>
            <div class="mySlides fade">
                <div class="numbertext">6 / 7</div>
                <img src="{{asset('images/6.JPG')}}" style="width:100%">
                <div class="text">IMSA</div>
            </div>
            <div class="mySlides fade">
                <div class="numbertext">7 / 7</div>
                <img src="{{asset('images/7.JPG')}}" style="width:100%">
                <div class="text">IMSA</div>
            </div>

            <!-- Next and previous buttons -->
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>

        <div class="p-10"></div>

    </div>

    <script>

        var slideIndex = 1;
        showSlides(slideIndex);

        // Next/previous controls
        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        // Thumbnail image controls
        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("mySlides");
            var dots = document.getElementsByClassName("dot");
            if (n > slides.length) {slideIndex = 1}
            if (n < 1) {slideIndex = slides.length}
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex-1].style.display = "block";
            dots[slideIndex-1].className += " active";
        }
    </script>

</x-guest-layout>
