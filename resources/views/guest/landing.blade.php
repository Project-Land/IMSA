<x-guest-layout>
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('IMSA') }}
            </h2>
        </div>
    </header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 mt-3">

        <div class="flex flex-wrap">

            <div class="w-full sm:w-3/5">
                <div class="sm:text-lg sm:mr-4 tracking-wide">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc fermentum pulvinar pharetra. Vestibulum non efficitur mauris. Pellentesque ultricies varius quam eu aliquam. Donec convallis metus vel libero aliquet, ullamcorper interdum odio sodales. Fusce viverra massa at aliquam consequat. Fusce id massa odio. Fusce consectetur, arcu et cursus consectetur, ante mauris fermentum quam, in viverra augue purus et nibh.Proin eget gravida mi. In sollicitudin et ex at mollis. Praesent ac sollicitudin erat. Maecenas non velit sit amet neque tristique lacinia. Vivamus ultricies ac felis imperdiet accumsan. Sed sollicitudin ullamcorper metus eu dictum. Aliquam erat volutpat. Proin condimentum tincidunt elit. Sed maximus magna nec orci porttitor, non condimentum tortor consectetur.
                    </p>
                    <p class="my-3">
                        Aliquam nunc lacus, faucibus in lacinia et, auctor at augue. Sed volutpat ante justo, rutrum blandit elit malesuada sit amet. Donec vulputate at lacus sed interdum. Morbi dapibus volutpat ligula id porttitor. Mauris in augue dignissim, pharetra est faucibus, euismod tortor. In a aliquam massa, vel pellentesque ligula. Mauris elementum dui sapien, ut lobortis elit lacinia et. Sed efficitur ex sed pellentesque cursus. Vestibulum efficitur est erat, vitae fermentum ipsum hendrerit eget. Interdum et malesuada fames ac ante ipsum primis in faucibus. Duis eu dapibus lacus. Proin scelerisque porttitor ante, eu suscipit tellus placerat faucibus. Sed justo ante, finibus sed quam eu, pretium efficitur dui. Curabitur nec sodales urna. Vivamus feugiat maximus elit, et bibendum magna consectetur eu. Nulla volutpat nisi a sem iaculis ultrices.
                    </p>
                </div>
            </div>
    
            <div class="w-full sm:w-2/5">
                <form action="" method="POST" id="contact-form" class="w-full max-w-lg">
                    @csrf
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3 mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">{{ __('Ime i prezime') }}</label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="name" name="name" type="text" required>
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="company">{{ __('Firma') }}</label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="company" name="company" type="text" required>
                        </div>
                    </div>
        
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="email">{{ __('Email') }}</label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="email" name="email" type="email" required>
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="date">{{ __('Željeni termin') }}</label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="date" id="date" type="text" placeholder="xx.xx.xxxx" required>
                        </div>
                    </div>

                    <!-- <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="message">{{ __('Poruka') }}</label>
                            <textarea class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="message" id="message" cols="30" rows="6"></textarea>
                        </div>
                    </div> -->

                    <div class="flex flex-wrap justify-end -mx-3 mb-6 float-right">
                        <div class="w-full px-3">
                            <button type="submit" class="shadow uppercase tracking-wide text-xs bg-gray-800 hover:bg-gray-700 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-6 rounded" type="button">{{ __('Pošalji') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $("#contact-form").validate({
            messages: {
                name: "{{ __('Polje je obavezno') }}",
                company: "{{ __('Polje je obavezno') }}",
                email: "{{ __('Polje je obavezno') }}",
                date: "{{ __('Polje je obavezno') }}",
            }
        });

        let lang = document.getElementsByTagName('html')[0].getAttribute('lang');
        $.datetimepicker.setLocale(lang);

        $('#date').datetimepicker({
            timepicker: true,
            format: 'd.m.Y H:i',
            minDate: 0,
            dayOfWeekStart: 1,
            scrollInput: false
        });
    </script>

</x-guest-layout>