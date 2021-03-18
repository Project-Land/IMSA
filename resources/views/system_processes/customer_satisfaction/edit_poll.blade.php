<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Zadovoljstvo korisnika')}} - {{__('Izmena ankete') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col-sm-2">
        	<a class="btn btn-light" href="{{ route('customer-satisfaction.index') }}"><i class="fas fa-arrow-left"></i> {{__('Nazad')}}</a>
     	</div>
	</div>


	<div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">
		<form action="{{ route('customer-satisfaction-poll.update', \Auth::user()->current_team_id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf
            @method('PUT')
            @for($i = 1; $i <= $poll->count(); $i++)
                @foreach($poll as $p)
                    @if($p->column_name == 'col'.$i)
                    <div class="mb-4">
                        <label for="col{{ $i }}" class="block text-gray-700 text-sm font-bold mb-2">{{__('Naziv kolone')}} {{ $i }}:</label>
                        <input type="text" class="appearance-none border w-4/5 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="col{{ $i }}" name="col{{ $i }}" value="{{ $p->name }}" @if($i == 1) autofocus required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')" @endif> 
                        
                        <button onclick="confirmDeleteModal({{ $p->id }})" type="button" class="ml-4 w-full md:w-auto bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline"><i class="fa fa-trash"></i></button>
                        
                        @error('col{{ $i }}')
                            <span class="text-red-700 italic text-sm">{{ $message }}</span>
                        @enderror
                       
                    </div>
                    @endif
                @endforeach
            @endfor

            @for($i = $poll->count()+1; $i <= 10; $i++)
                <div class="mb-4">
                    <label for="col{{ $i }}" class="block text-gray-700 text-sm font-bold mb-2">{{__('Naziv kolone')}} {{ $i }}:</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="col{{ $i }}" name="col{{ $i }}" value="">
                    @error('col{{ $i }}')
                        <span class="text-red-700 italic text-sm">{{ $message }}</span>
                    @enderror
                </div>
            @endfor

			<button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{__('Izmeni')}}</button>
		</form>
	</div>


    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="px-6 py-4">
                    <div class="text-lg">{{ __('Brisanje unosa ankete')}}</div>
                    <div class="mt-4">{{ __('Da li ste sigurni?')}}</div>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Odustani')}}</button>
                    <a class="btn-ok hover:no-underline cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150 ml-2">{{ __('Obriši')}}</a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>


<script>

function confirmDeleteModal($id){
        let id = $id;
        $('#confirm-delete-modal').modal();
        $('#confirm-delete-modal').on('click', '.btn-ok', function(e) {
            let form = `<form id="form${id}" action="/customer-satisfaction/delete-col/${id}" method="POST">
            @csrf
            </form>`;
            document.body.innerHTML+=form;
            form=document.getElementById('form'+id);
            form.submit();
            
        });
    }

</script>