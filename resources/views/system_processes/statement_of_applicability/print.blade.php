<div >


    <div ><h2 style="text-align:center;">{{__('Izjava o primenljivosti')}}</h2></div>

    @php $index=0; @endphp
    <table style="border-collapse:separate;" border="solid black 1">
                        <thead>
                            <tr class="text-center font-bold">
                                <td class="w-1/6"><b>{{ __('Naziv') }}</b></td>
                                <td class="w-2/6"><b>{{ __('Opis') }}</b></td>
                                <td class="w-1/6"><b>{{ __('Status') }}</b></td>
                                <td class="w-2/6"><b>{{ __('Komentar') }}</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($soas as $soa)
                            @if(($index < 14 && $soa->soaField->soa_field_group_id != $groups[$index]->id) )
                            @php $index++; @endphp

                            <tr>
                                <th colspan="4" style='padding:2px'>{{ __($groups[$index]->name) }}</th>
                            </tr>

                           @else
                           @if($loop->iteration==1 )
                            <tr>
                                <th colspan="4" style='padding:2px'>{{ __($groups[$index]->name) }}</th>
                            </tr>

                            @endif


                            @endif
                                <tr>
                                    <td style='padding:2px;'>{{ __($soa->soaField->name) }}</td>
                                    <td style='padding:2px;'>{{ __($soa->soaField->description) }}</td>
                                    <td style='padding:2px;'>{{ ($soa->status !=  null)? __($soa->status) : "/" }}</td>
                                    <td style='padding:2px;'>
                                        {{ $soa->comment != null ? __($soa->comment) : '/' }}
                                        @if($soa->documents->count() != 0)
                                            <p class="font-bold text-sm mt-2 mb-0">{{ __('Relevantna dokumenta') }}:</p>
                                            @foreach($soa->documents as $doc)
                                            {{$doc->document_name }}
                                            <br>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>

                            @endforeach

                        </tbody>
                    </table>


</div>

<script>window.print()</script>
