<x-app-layout>
    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Izjava o primenljivosti')}} - {{__('Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col-sm-2">
        	<a class="btn btn-light" href="{{ route('statement-of-applicability.index') }}"><i class="fas fa-arrow-left"></i> {{__('Nazad')}}</a>
     	</div>
	</div>

	<div class="mx-auto md:w-full mt-1 md:p-10 sm:p-2 rounded" x-data="{ @foreach($groups as $g) open{{ $g->id }}:false, @endforeach }">
		<form action="{{ route('statement-of-applicability.update', \Auth::user()->current_team_id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" id="form">
			@csrf
			@method('PUT')

            @foreach($groups as $group)
                <div class="flex flex-grow soa-group" id="title-group-{{ $group->id }}" onclick="checkGroup(this.id)">
                    <p class="font-bold cursor-pointer" @click="open{{ $group->id }} = ! open{{ $group->id }}">{{ $group->name }} <i class="ml-2 fas" :class="{'fa-chevron-up': open{{ $group->id }}, 'fa-chevron-down': ! open{{ $group->id }} }"></i></p>
                    <span id="span-error" class="d-none text-red-500"><i class="fa fa-exclamation-triangle ml-4"></i></span>
                    <span id="span-success" class=" text-green-500"><i class="fa fa-check ml-4"></i></span>
                </div>
                @foreach($fields as $field)
                    @if($group->id == $field->soaField->soa_field_group_id)
                        <div id="{{ $field->id }}" data-group="group-{{ $group->id }}" class="flex flex-wrap border-b-2 py-2 my-2 main-block group-{{ $group->id }} title-group-{{ $group->id }}" :class="{'': open{{ $group->id }}, 'hidden': ! open{{ $group->id }} }">
                            <div class="w-full sm:w-1/5">
                                <label class="block text-gray-700 text-sm font-bold mb-2">{{__('Naziv kontrole')}}:</label>
                                <p class="text-xs sm:text-sm">{{ $field->soaField->name }}</p>
                            </div>
                            <div class="w-full sm:w-1/5">
                                <label class="block text-gray-700 text-sm font-bold mb-2">{{__('Opis kontrole')}}:</label>
                                <p class="text-xs sm:text-sm">{{ $field->soaField->description }}</p>
                            </div>
                            <div class="w-full sm:w-1/5">
                                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">{{__('Status kontrole')}}:</label>
                                <select class="text-xs sm:text-sm mr-2 block border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="{{ $field->id }}[status]" data-id="{{ $field->id }}" onChange="showDocument(this)" required oninvalid="this.setCustomValidity('{{__("Izaberite status")}}')" oninput="this.setCustomValidity('')">
                                    <option value="">{{ __('Izaberi') }}...</option>
                                    <option value="Prihvaćeno" {{ $field->status == 'Prihvaćeno' ? 'selected':'' }}>{{ __('Prihvaćeno') }}</option>
                                    <option value="Neprihvaćeno" {{ $field->status == 'Neprihvaćeno' ? 'selected':'' }}>{{ __('Neprihvaćeno') }}</option>
                                    <option value="Nije primenljivo" {{ $field->status == 'Nije primenljivo' ? 'selected':'' }}>{{ __('Nije primenljivo') }}</option>
                                </select>
                                @error('status')
                                    <span class="text-red-700 italic text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="w-full sm:w-1/5">
                                <label for="comment" class="block text-gray-700 text-sm font-bold mb-2">{{__('Komentar')}}:</label>
                                <textarea class="text-xs sm:text-sm appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="{{ $field->id }}[comment]" oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')">{{ $field->comment }}</textarea>
                                @error('comment')
                                    <span class="text-red-700 italic text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="w-full sm:w-1/5 pl-4 {{ ($field->status != "Prihvaćeno")? 'd-none':'' }}" id="document_col{{ $field->id }}">
                                <label for="documents" class="block text-gray-700 text-sm font-bold mb-2">{{__('Dokumenti')}}:</label>
                                <select class="js-example-basic-multiple" style="width: 100%; border-radius: 0;" name="{{ $field->id }}[document][]" id="select{{ $field->id }}" multiple="multiple">
                                    <optgroup label="Politike">
                                        @foreach($alldocuments as $document)
                                            @if($document->doc_category === 'policy')
                                                <option data-folder="{{ $document->doc_category }}" data-file="{{ $document->file_name }}" value="{{ $document->id }}" {{ $field->documents->pluck('id')->contains($document->id) ? "selected":"" }}>{{ $document->document_name }}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Procedure">
                                        @foreach($alldocuments as $document)
                                            @if($document->doc_category === 'procedure')
                                                <option data-folder="{{ $document->doc_category }}" data-file="{{ $document->file_name }}" value="{{ $document->id }}" {{ $field->documents->pluck('id')->contains($document->id) ? "selected":"" }}>{{ $document->document_name }}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                </select>

                                @foreach($field->documents as $document)
                                    <div id="bl-select{{ $field->id }}-{{ $document->id }}">
                                        <div id="form-{{ $field->id }}-{{ $document->id }}" class="inline" action="{{ route('document.preview') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="folder" value="{{ Str::snake(Auth::user()->currentTeam->name).'/'.$document->doc_category }}">
                                            <input type="hidden" name="file_name" value="{{ $document->file_name }}">
                                            <button class="button text-primary cursor-pointer text-sm" type="submit" formtarget="_blank" onclick="previewDocument(event)">{{ $document->document_name }}</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach

			<button type="button" onclick="formSubmit()" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{__('Sačuvaj')}}</button>
		</form>
	</div>

</x-app-layout>

<script>

    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();

        for(let select of document.getElementsByClassName('js-example-basic-multiple')){
            let mainblock = document.createElement('div');
            mainblock.setAttribute('id', 'mainblock-'+select.id);
            $('#'+select.id).on('select2:select', function (e) {
                let file = e.params.data.element.dataset.file;
                let folder = e.params.data.element.dataset.folder;
                let block = document.createElement('div');
                block.setAttribute('id', 'bl-'+select.id+'-'+e.params.data.id)
                let doc_element =`
                            <form class="inline" action="{{ route('document.preview') }}" method="POST">
                                @csrf
                                <input type="hidden" name="folder" value="{{ Str::snake(Auth::user()->currentTeam->name).'/' }}`+folder+`">
                                <input type="hidden" name="file_name" value="`+file+`">
                                <button class="button text-primary cursor-pointer text-sm" type="submit" formtarget="_blank">`+e.params.data.text+`</button>
                            </form>`;
                block.innerHTML = doc_element;
                mainblock.appendChild(block);
                e.target.parentElement.insertAdjacentElement('beforeend', mainblock);
            });

            $('#'+select.id).on('select2:unselect', function (e) {
                document.getElementById('bl-'+select.id+'-'+e.params.data.id).remove();
            });
        }
    });

    function showDocument(obj){
        if(obj.value == "Prihvaćeno"){
            document.getElementById(obj.dataset.id).lastElementChild.classList.remove('d-none');
        }
        else{
            $("#"+document.getElementById(obj.dataset.id).lastElementChild.querySelector("select").id).val(null).trigger('change');
            document.getElementById(obj.dataset.id).lastElementChild.classList.add('d-none');
            obj.parentElement.parentElement.lastElementChild.lastElementChild.remove();
        }
    }

    function formSubmit(){
        let block = document.getElementsByClassName('main-block');

        let error = false;

        for(let div of block ){

            let group = div.dataset.group;
            let groupRows = document.getElementsByClassName(group);

            let groupTitleBlock = document.getElementById('title-'+group);
            groupTitleBlock.querySelector('#span-success').classList.remove('d-none');

            let status = div.querySelector('select').value;
            let comment = div.querySelector('textarea').value;
            let documents = div.lastElementChild.querySelector('select').value;

            if(status == "Prihvaćeno"){
                if(comment != "" || documents != ""){
                    div.classList.remove('border-2');
                    div.classList.remove('border-red-500');
                    groupTitleBlock.querySelector('#span-success').classList.remove('d-none');
                    groupTitleBlock.querySelector('#span-error').classList.add('d-none');
                }

                else{
                    div.classList.add('border-2');
                    div.classList.add('border-red-500');
                    error = true;

                    groupTitleBlock.querySelector('#span-success').classList.add('d-none');
                    groupTitleBlock.querySelector('#span-error').classList.remove('d-none');

                    for(let divs of groupRows){
                        divs.classList.remove('hidden');
                    }

                }
            }
            else if (status == ""){
                error = true;
                div.classList.add('border-2');
                div.classList.add('border-red-500');

                for(let divs of groupRows){
                    divs.classList.remove('hidden');
                }

                groupTitleBlock.querySelector('#span-success').classList.add('d-none');
                groupTitleBlock.querySelector('#span-error').classList.remove('d-none');
            }
            else{
                div.classList.remove('border-2');
                div.classList.remove('border-red-500');
                groupTitleBlock.querySelector('#span-success').classList.remove('d-none');
                groupTitleBlock.querySelector('#span-error').classList.add('d-none');
            }
        }

        if(error){
            return
        }

        document.getElementById('form').submit();
    }

    function previewDocument(e){
        e.preventDefault();
        let form = document.createElement('form');
        form.method = "POST";
        form.action = "/file-preview";
        form.innerHTML = e.target.parentElement.innerHTML;
        form.target = "_blank";
        document.body.append(form);
        form.submit();
    }

    function checkGroup(group){
        let doc=document.getElementsByClassName(group);
        let err=false;
        var spanS;
        var spanE;
        for(let row of doc){
            let group=row.dataset.group;
            let groupTitleBlock = document.getElementById('title-'+group);
            let spanSuccess = groupTitleBlock.querySelector('#span-success');
            let spanError = groupTitleBlock.querySelector('#span-error');
            let status = row.querySelector('select').value;
            let comment = row.querySelector('textarea').value;
            let documents = row.lastElementChild.querySelector('select').value;

            if(status == "Prihvaćeno"){
                if(comment != "" || documents != ""){

                    spanS=groupTitleBlock.querySelector('#span-success');
                    spanE=groupTitleBlock.querySelector('#span-error');
                }else{
                    err=true;
                    spanS=groupTitleBlock.querySelector('#span-success');
                    spanE=groupTitleBlock.querySelector('#span-error');
                    break;
                }
            }
            else if (status == ""){
                err = true;
                spanS=groupTitleBlock.querySelector('#span-success');
                spanE=groupTitleBlock.querySelector('#span-error');
                break;
            }
            else{

            }
            }
            if(err){
                spanS.classList.add('d-none');
                spanE.classList.remove('d-none');
            }else{
                spanS.classList.remove('d-none');
                spanE.classList.add('d-none');
            }
           
    }

</script>

<style>
    .select2-results {
        font-size: 0.875rem;
    }
    .select2-container--default .select2-selection--multiple {
        border-radius: 0;
        border: 1px solid #dee2e6 !important;

    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border: 1px solid #dee2e6 !important;
    }
    .select2-selection__choice {
        font-size: 0.875rem;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        border-radius: 1px;
    }

    .select2-selection, .select2-selection--multiple{
        padding-top: 5px;
        padding-bottom: 10px;
    }
</style>
