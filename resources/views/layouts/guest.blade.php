<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'IMSA') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.js" defer></script>

        <script src="https://kit.fontawesome.com/f94836499c.js" crossorigin="anonymous"></script>

        <style>


#i {
	position: relative;
	display: block;
	width:65vw;
	min-width:55vw;
	height:35vw;
	overflow: hidden;
	border-radius: 5px;
}

#i:before, #i:after {
	content: '<';
	position: absolute;
	top: 50%;
	left: 1rem;
	z-index: 2;
	width: 2rem;
	height: 2rem;
	background: dodgerblue;
	color: white;
	border-radius: 50%;
	display: flex;
	justify-content: center;
	align-items: center;
	pointer-events: none;
}

#i:after {
	content: '>';
	left: auto;
	right: 1rem;
}

/* I haven't found a way for IE and Edge to let me style inputs that way */
.sliderInput {
	appearance: none;
	-ms-appearance: none;
	-webkit-appearance: none;
	display: block;
	width: 100%;
	height: 100%;
	position: absolute;
	top: 0;
	left: 0;
	border-radius: 5px;
	background-repeat: no-repeat;
	background-size: cover;
	background-position: center;
	transform: translateX(100%);
	transition: transform ease-in-out 400ms;
	z-index: 1;
}

.sliderInput:focus {
	outline: none;
}

.sliderInput:after {
	
	position: absolute;
	top: 1rem;
	left: 1rem;
	background-color: rgba(0,0,0,0.4);
	color: white;
	padding: .5rem;
	font-size: 1rem;
	border-radius: 5px;
}

.sliderInput:not(checked):before {
	content: '';
	position: absolute;
	width: 2rem;
	height: 2rem;
	border-radius: 50%;
	top: 50%;
	left: calc(-100% + 1rem);
}

.sliderInput:checked:before {
	display: none;
	left: 1rem;
}

.sliderInput:checked {
	transform: translateX(0);
	pointer-event: none;
	z-index: 0;
	box-shadow: -5px 10px 20px -15px rgba(0,0,0,1);
}

.sliderInput:checked + input:before {
	left: -3rem;
}

.sliderInput:checked + input ~ input:before {
	display: none;
}

@media screen and (max-width: 600px) {
  #i {width:100%;
  height:50vw;
  
  }
}

        </style>
    </head>
    <body>

        @include('guest_navigation')

        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>
    </body>
</html>
