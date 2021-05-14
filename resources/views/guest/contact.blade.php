<x-guest-layout>

    @push('meta')
        <meta name="description" content="IMSA aplikacija za unapređenje biznisa, iso, standardi, kontaktirajte nas">
        <meta name="keywords" content="imsa, kontakt, kontaktirajte, nas, digitalni, alat, za, unapređenje, biznisa, iso, standardi">
        <meta name="author" content="Projectland Serbia">

        <meta property="og:title" content="IMSA {{ __('aplikacija') }}, {{ __('digitalni alat za unapređenje biznisa') }}" />
        <meta property="og:description" content="{{ __('IMSA aplikacija je kreirana u saradnji sa konsultantskim ekspertima u oblasti implementacije i sertifikacije ISO standarda, tako da je dizajnirana po svetskim standardima.') }}" />
        <meta property="og:url" content="http://quality4.me/contact" />
        <meta property="og:image" content="{{ asset('/images/imsa_logo_og.jpg') }}" />
        <meta property="og:image:type" content="image/jpeg" />
        <meta property="og:image:alt" content="IMSA {{ __('aplikacija') }}" />

        <meta property="og:type" content="website" />
        <meta property="og:locale" content="sr_RS" />
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js" integrity="sha512-UdIMMlVx0HEynClOIFSyOrPggomfhBKJE28LKl8yR3ghkgugPnG6iLfRfHwushZl1MOPSY6TsuBDGPK2X4zYKg==" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
    @endpush

    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" />
        <style>
            label.error{
                font-style: italic;
                color: red;
                font-size: .75rem;
            }
        </style>
    @endpush

    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800">
                    {{ __('Kontaktirajte nas') }}
                </h2>
                <div class="space-x-2">
                    <a href="https://www.facebook.com/IMSAaplikacija/" target="_blank"><i class="fab fa-facebook-square fa-2x" style="color: #4267B2"></i></a>
                    <a href="https://rs.linkedin.com/in/projectland-serbia-03112a209" target="_blank"><i class="fab fa-linkedin fa-2x" style="color: #2867B2"></i></a>
                    <a href="https://www.youtube.com/channel/UClipICMNZjQ2tznqhVBbt-w" target="_blank"><i class="fab fa-youtube fa-2x" style="color: #FF0000"></i></a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 mt-3">

        @if(Session::has('message'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 mb-6 rounded relative alert" role="alert">
                <span class="block sm:inline">{{ Session::get('message') }}</span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 focus:outline-none">
                    <span class="text-2xl close">&times;</span>
                </button>
            </div>
        @endif

        <div class="flex flex-wrap">

            <div class="w-full sm:w-3/5" x-data="{open1: true, open2: true}">
                <div class="sm:text-lg sm:mr-4 tracking-wide">

                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 mb-3">
                        <h1 class="font-semibold text-xl text-gray-800 leading-tight border-b-8 border-red-500 text-center">
                        <span class="text-red-500 font-bold text-2xl">{{ __('IMSA').' ' }}</span><span class="text-yellow-300 font-bold text-2xl">{{__('aplikacija')}}</span>
                        </h1>
                        <h3 class="text-center text-2xl">{{__('digitalni alat za unapređenje biznisa')}}</h3>
                    </div>

                    <p>
                        {{__('IMSA aplikacija je kreirana u saradnji sa konsultantskim ekspertima u oblasti implementacije i sertifikacije ISO standarda, tako da je dizajnirana po svetskim standardima.')}}
                    </p>

                    <h2 class="sm:text-xl font-bold my-3"><i class="fas fa-mobile-alt mr-2 text-red-600"></i>{{__('Postoji 199 aplikacija. Zašto baš IMSA?')}}</h2>
                    <ul class="list-disc pl-4">
                        <li>{{ __('Objedinjeni su svi sistemski procesi i zahtevi ISO standarda. Primenjiva je na nivou čitave organizacije bez obzira na privrednu delatnost.') }}</li>
                    </ul>

                    <h2 class="sm:text-xl font-bold my-3"><i class="fas fa-laptop mr-2 text-red-600"></i>{{__('Zašto digitalizovati poslovanje?')}}</h2>
                    <ul class="list-disc pl-4">
                        <li>{{ __('Nema više registratora, troškova nabavke papira, štampača, tonera.') }}</li>
                        <li>{{ __('Bezbednost dokumentacije (ako se poplavi magacin ili izbije požar i izgube podaci...)') }}</li>
                        <li>{{ __('Ekološka odgovornost.') }}</li>
                    </ul>

                    <h2 class="sm:text-xl font-bold my-3"><i class="far fa-clock mr-2 text-red-600"></i>{{__('Koliko vremena provedete u obuci zaposlenih?')}}</h2>
                    <ul class="list-disc pl-4">
                        <li>{{ __('IMSA je kreirana da uštedi vreme zaposlenima jer su jasno definisani dokumentacija, sistemski procesi, kao i notifikacije u skladu s rokovima - stižu i na mejl i predstavljaju pomoć menadžerima procesa u efektivnom izvršavanju zaduženja - bez propusta i kašnjenja.') }}</li>
                    </ul>

                    <h2 class="sm:text-xl font-bold my-3"><i class="fas fa-cut mr-2 text-red-600"></i>{{__('Da li su mi pored IMSA aplikacije potrebne i consulting usluge?')}}</h2>
                    <ul class="list-disc pl-4">
                        <li>{{ __('Uz korišćenje IMSA aplikacije troškovi consultinga značajno se umanjuju ili u nekim slučajevima eliminišu jer IMSA aplikacija obezbeđuje redovno ažuriranje i prilagođavanje sa izlaskom novih verzija ISO standarda.') }}</li>
                    </ul>

                    <h2 class="sm:text-xl font-bold my-3"><i class="fas fa-balance-scale-right mr-2 text-red-600"></i>{{__('Zašto je bolje iznajmiti nego kupiti IMSU?')}}</h2>
                    <ul class="list-disc pl-4">
                        <li>{{ __('Možete odustati u svakom trenutku') }}</li>
                        <li>{{ __('Ušteda u IT podršci – redovno ažuriranje i apdejtovanje aplikacije su uračunate u cenu - dakle, nema dodatnih troškova kasnije.') }}</li>
                    </ul>

                    <h2 class="sm:text-xl font-bold my-3"><i class="fas fa-lock mr-2 text-red-600"></i>{{__('Da li su moje informacije bezbedne?')}}</h2>
                    <ul class="list-disc pl-4">
                        <li>{{ __('DA. Jedini vlasnici podataka ste vi, naša IT podrška isključivo pokriva segmente aplikacije ali ne i sadržaj.') }}</li>
                    </ul>

                    <h2 class="sm:text-xl font-bold my-3"><i class="fas fa-exclamation-triangle mr-2 text-red-600"></i></i>{{__('Ima li IMSA manu?')}}</h2>
                    <ul class="list-disc pl-4">
                        <li>{{ __('Ima. Mnogo će vam olakšati poslovanje, pa će vas malo ⹂razmaziti”.') }}</li>
                    </ul>

                    <h2 class="sm:text-xl font-bold my-3 cursor-pointer" @click="open1 = ! open1">{{__('Kome je namenjena?')}} <i class="ml-2 fas" :class="{'fa-chevron-up': open1, 'fa-chevron-down': ! open1 }"></i></h2>
                    <div :class="{'': open1, 'hidden': ! open1 }">
                        <ul>
                            <li><i class="fas fa-check mr-1 text-green-400"></i> {{__('Svima koji žele da unaprede svoj sistem menadžmenta.')}}</li>
                            <li><i class="fas fa-check mr-1 text-green-400"></i> {{__('Organizacijama koje imaju implementirane ISO standarde.')}}</li>
                            <li><i class="fas fa-check mr-1 text-green-400"></i> {{__('Organizacijama koje treba da uvedu ISO standard - IMSA omogućava da proces implementacije sprovedu brzo, lako i bez angažovanja konsultantskih kuća.')}}</li>
                        </ul>
                    </div>

                    <h2 class="sm:text-xl font-bold my-3 cursor-pointer" @click="open2 = ! open2">{{__('Benefiti primene aplikacije su:')}} <i class="ml-2 fas" :class="{'fa-chevron-up': open2, 'fa-chevron-down': ! open2 }"></i></h2>
                    <div :class="{'': open2, 'hidden': ! open2 }">
                        <ul>
                            <li><i class="fas fa-check mr-1 text-green-400"></i>{{__('sistemsko upravljanje rizicima')}}</li>
                            <li><i class="fas fa-check mr-1 text-green-400"></i>{{__('centralizovano i sistemsko praćenje ciljeva, kao i stepena njihove realizacije')}}</li>
                            <li><i class="fas fa-check mr-1 text-green-400"></i>{{__('jasno i pregledno praćenje potreba i zahteva zainteresovanih strana')}}</li>
                            <li><i class="fas fa-check mr-1 text-green-400"></i>{{__('olakšano praćenje performansi i ocenjivanje isporučilaca')}}</li>
                            <li><i class="fas fa-check mr-1 text-green-400"></i>{{__('značajno ubrzan i pojednostavljen proces preispitivanja od strane rukovodstva')}}</li>
                            <li><i class="fas fa-check mr-1 text-green-400"></i>{{__('unapređen proces upravljanja reklamacijama')}}</li>
                            <li><i class="fas fa-check mr-1 text-green-400"></i>{{__('modernizovano planiranje i organizovanje internih provera')}}</li>
                            <li><i class="fas fa-check mr-1 text-green-400"></i>{{__('ažurnost korišćene dokumentacije')}}</li>
                            <li><i class="fas fa-check mr-1 text-green-400"></i>{{__('smanjen rizik od gubljenja/suspenzije sertifikata')}}</li>
                            <li><i class="fas fa-check mr-1 text-green-400"></i>{{__('pristup 24/7 s bilo kog računara, laptopa, tablet, smart telefona uz internet konekciju.')}}</li>
                        </ul>
                    </div>

                    <h2 class="sm:text-xl font-bold my-3">{{__('Standardi')}}:</h2>
                    <div class="">
                        <div class=""><i class="fas fa-star text-yellow-400"></i> ISO 9001</div>
                        <div class=""><i class="fas fa-star text-yellow-400"></i> ISO 14001</div>
                        <div class=""><i class="fas fa-star text-yellow-400"></i> ISO 27001</div>
                        <div class=""><i class="fas fa-star text-yellow-400"></i> ISO 45001</div>
                    </div>

                    <p class="my-5 ">{!!'<b>'.__('NAPOMENA').':</b>'!!} {{__('Prema zahtevu, IMSA može biti kreirana za standard koji Vam je potreban.')}}</p>

                </div>
            </div>

            <div class="w-full sm:w-2/5 mt-5">
                <h2 class="text-2xl mb-5 font-bold">{{__('Kontaktirajte nas ili zakažite besplatnu prezentaciju')}}</h2>
                <form action="{{route('contactme')}}" method="get" id="contact-form" class="w-full max-w-lg">
                    @csrf
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">{{ __('Ime i prezime') }}</label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="name" name="name" type="text" autocomplete="off" required>
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="company">{{ __('Firma') }}</label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="company" name="company" type="text" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="email">{{ __('Email') }}</label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="email" name="email" type="email" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="date">{{ __('Željeni termin') }}</label>
                            <input readonly class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="date" id="date" type="text" autocomplete="off" placeholder="xx.xx.xxxx">
                            <span class="text-sm">({{__('Popunjavate ukoliko zakazujete prezentaciju')}})</span>
                        </div>
                    </div>

                     <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3 mb-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="message">{{ __('Poruka') }}</label>
                            <textarea class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4  leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="message" id="message" cols="30" rows="6"></textarea>
                            <span class="text-sm">({{__('Poruka nije obavezna')}})</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap justify-end -mx-3 mb-6 float-right">
                        <div class="w-full px-3">
                            <button type="submit" class="shadow uppercase tracking-wide text-xs bg-gray-800 hover:bg-gray-700 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-6 rounded" type="button">{{ __('Pošalji') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                    <a class="text-center text-lg sm:text-3xl mt-4" href="tel:+38162237204">+38162/237-204</a>
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

    <script>
        $("#contact-form").validate({
            messages: {
                name: "{{ __('Polje je obavezno') }}",
                company: "{{ __('Polje je obavezno') }}",
                email: "{{ __('Polje je obavezno') }}",
            }
        });

        let lang = document.getElementsByTagName('html')[0].getAttribute('lang');
        $.datetimepicker.setLocale(lang);

        $('#date').datetimepicker({
            timepicker: true,
            format: 'd.m.Y H:i',
            minDate: 0,
            dayOfWeekStart: 1,
            scrollInput: false,
            step:30,
            minTime:"08:00",
            maxTime:"17:00",
            beforeShowDay: function (date) {
                if (date.getDay() > 0 && date.getDay() < 6) {
                    return [true, ''];
                } else {
                    return [false, ''];
                }
            }
        });

        $(".alert").fadeTo(5000, 500).slideUp(500, function(){
            $(".alert").slideUp(500);
        });

        $(".close").click( () => {
            $(".alert").hide();
        });

    </script>
</x-guest-layout>
