<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista firmi') }}
        </h2>
    </x-slot>

    <div class="row mt-1">
        <div class="col">
            @if(Session::has('status'))
                <div class="alert alert-info alert-dismissible fade show">
                    {{ Session::get('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="flex flex-col">
		<div class="-my-2 overflow-x-auto md:overflow-x-visible sm:-mx-6 lg:-mx-8">
			<div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
				<div class="shadow border-b border-gray-200 sm:rounded-lg">
					<table class="min-w-full divide-y divide-gray-200">
						<thead>
							<tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Firma
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Standardi
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Sadržaj
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Br. korisnika
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Logovi
                                </th>
                                <th class="px-6 py-3 bg-gray-50"></th>
							</tr>
						</thead>
						<tbody class="bg-white divide-y divide-gray-200">
							@foreach($teams as $team)
							<tr>
								<td class="px-6 py-4 whitespace-no-wrap">
									{{ $team->name }}
								</td>
								<td class="py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                    <div class="relative inline-block text-left">
                                        <div class="inline-block">
                                            <x-jet-dropdown width="48">

                                                <x-slot name="trigger">
                                                    <div>
                                                        <span class="shadow-sm">
                                                            <button type="button" class="inline-flex justify-center w-full px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150">
                                                                Standardi
                                                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </x-slot>

                                                <x-slot name="content">
                                                    <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg">
                                                        <div class="rounded-md bg-white shadow-xs">
                                                            @foreach($team->standards as $standard)
                                                                <div class="py-1 flex justify-between hover:bg-gray-200">
                                                                    <p class="text-center px-4 py-1 text-sm m-1 leading-5 text-gray-700 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">{{ $standard->name }}</p>
                                                                    <form action="{{ route('standards.destroy', $standard->id) }}" method="POST">
                                                                        @method('DELETE')
                                                                        @csrf
                                                                        <input type="hidden" name="team_id" value="{{ $team->id }}">
                                                                        <button class="text-center px-4 py-1 m-1 text-sm leading-5 text-red-700 hover:text-red-900 focus:outline-none focus:bg-red-100 focus:text-red-900" type="submit" style="cursor: pointer;" onclick="return confirm('Da li ste sigurni?');"><i class="fas fa-trash"></i></button>
                                                                    </form>
                                                                </div>
                                                                <div class="border-t border-gray-100"></div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </x-slot>

                                            </x-jet-dropdown>
                                        </div>
                                        <a class="inline-block px-2" href="{{ route('standards.create-new', $team->id) }}"><i class="fas fa-plus"></i></a>
                                    </div>
                                </td>
                                <td class="py-4 px-6 whitespace-no-wrap text-sm leading-5 text-gray-500">
									<a href="/change-current-team/{{ $team->id }}">Sadržaj</a>
                                </td>
                                <td class="py-4 px-6 whitespace-no-wrap text-sm leading-5 text-gray-500">
									{{ $team->users->count() - 1 }}
                                </td>
                                <td class="py-4 px-6 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                    <a href="{{ route('logs.show', $team->name) }}">Log</a>
								</td>
								<td class="py-4 px-6 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                    <a href="/teams/{{ $team->id }}" class="text-indigo-600 hover:text-indigo-900">Izmeni</a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

</x-app-layout>
