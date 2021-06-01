<div >
    <h2 style="text-align: center;">{{ __('Zadovoljstvo korisnika') }}</h2>

    <table style="border-collapse: separate;" border="solid black 1">
        <thead>
            <tr>
                <th style="padding: 5px;">{{ __('Klijent')}}</th>
                @for($i = 0; $i < $poll->count(); $i++)
                    <th style="padding: 5px;">{{ $poll[$i]->name }}</th>
                @endfor
                @if($poll->count() <= 4)
                    <th style="padding: 5px;">{{ __('Napomena') }}</th>
                    <th style="padding: 5px;">{{ __('Datum') }}</th>
                @endif
                <th style="padding: 5px;">{{ __('Prosek') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cs as $row)
            <tr>
                <td style="padding: 5px; text-align: center;">{{ $row->customer }}</td>
                @foreach($poll as $po)
                    <td style="padding: 5px; text-align: center;">{{ $row->{$po->column_name} ?? "/" }}</td>
                @endforeach

                @if($poll->count() <= 4)
                    <td style="padding: 5px;">{{ $row->comment ?? "/" }}</td>
                    <td style="padding: 5px; text-align: center;">{{ $row->date ? date('d.m.Y', strtotime($row->date)) : $row->created_at->format('d.m.Y') }}</td>
                @endif
                <td style="padding: 5px; font-weight: bold; text-align: center;">{{ $row->average() }}</td>
            </tr>
            @endforeach
            @if($cs->count())
                <tr style="font-weight: bold; text-align: center;">
                    <td>{{__('Prosek')}}</td>
                    @foreach($poll as $po)
                        <td style="padding: 5px;">@if($cs->count()) {{ ($cs->sum($po->column_name)/$cs[0]->columnCount($po->column_name)) == 0 ? "/" : round($cs->sum($po->column_name)/$cs[0]->columnCount($po->column_name), 1) }} @endif</td>
                    @endforeach
                    @if($poll->count() <= 4)
                        <td></td>
                        <td></td>
                    @endif
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>

</div>

<script>window.print()</script>
