<x-guest-layout>

    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto pt-4 pb-4 px-4 sm:px-6 lg:px-8">
            {{ __('O aplikaciji') }}
        </div>
    </header>

    <div class="container mt-5 mx-auto text-lg p-2">

        <p class="w-75">
            Aplikacija podrazumeva efikasno i pouzdano rešenje koje je jednostavno za upotrebu i predstavlja neophodan korak ka unapređenju sistema menadžmenta.
            Predstavlja pouzdan alat za otklanjanje nepotrebnih gubitaka koji se javljaju zbog spore distribucije dokumenata, nepotrebnih troškova štampanja dokumenata, neprecizne kontrole pristupa dokumentima, česte upotrebe nevalidne verzije dokumentacije, nepravovremenog ili neadekvatnog obaveštavanja zaposlenih o svim izmenama u sistemu, i dr.
        </p>

        Osim opštih, specifični benefiti primene aplikacije su:
        <ul class="ml-5">
            <li><i class="fas fa-check"></i> Obezbeđuje ažurnost korišćene dokumentacije</li>
            <li><i class="fas fa-check"></i> Olakšava planiranje i organizovanje internih provera</li>
            <li><i class="fas fa-check"></i> Obezbeđuje sistemsko upravljanje rizicima</li>
            <li><i class="fas fa-check"></i> Obezbeđuje jasno i pregledno praćenje potreba i zahteva zainteresovanih strana</li>
            <li><i class="fas fa-check"></i> Olakšava praćenje performansi i ocenjivanje isporučilaca</li>
            <li><i class="fas fa-check"></i> Značajno ubrzava i pojednostavljuje proces preispitivanja od strane rukovodstva</li>
            <li><i class="fas fa-check"></i> Omogućava centralizovano i sistemsko praćenje ciljeva, kao i stepena njihove realizacije</li>
            <li><i class="fas fa-check"></i> Olakšava proces upravljanja reklamacijama</li>
            <li><i class="fas fa-check"></i> Smanjuje rizik od gubljenja/suspenzije sertifikata</li>
        </ul>

        <div id="i" class="mt-10 mx-auto" >
            <input class="sliderInput" checked type="radio" name="s" style="background-image: url({{asset('images/aplikacija5.png')}});" >
            <input class="sliderInput"  type="radio" name="s" style="background-image: url({{asset('images/aplikacija4.png')}});" >
            <input class="sliderInput" type="radio" name="s" style="background-image: url({{asset('images/aplikacija2.png')}});" >
            <input class="sliderInput" type="radio" name="s" style="background-image: url({{asset('images/aplikacija3.png')}});" >
        </div>

        <div class="p-10"></div>

    </div>

</x-guest-layout>
