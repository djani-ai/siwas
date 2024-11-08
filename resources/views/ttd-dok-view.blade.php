{{-- Tanda Tangan --}}
<table class="footer" width="100%" style="table-layout: fixed;">
    <tr>
        <td>Brondong,
            {{ date('d F Y', strtotime($record->tanggal_lap_seng)) }}
        </td>
    </tr>
    <tr>
        <td align="right"><img width="250px" src={{ url('storage/' . $record->user->ttd) }} alt="ttd"></td>
    </tr>
    <tr>
        <td class="np">
            {{ $record->user->name }}
        </td>
    </tr>
</table>


{{-- DOKUMENTASI --}}
@if ($record->dok1)
    <div class="page-break"></div>
    <table align="center">
        <tr>
            <td clas="c50"></td>
            <td clas="c50"></td>
        </tr>
        <br><br><br>
        <tr>
            <td><img width="300px" src={{ url('storage/' . $record->dok1) }} alt="ttd"></td>
            @if ($record->dok2)
                <td><img width="300px" src={{ url('storage/' . $record->dok2) }} alt="ttd"></td>
            @endif
        </tr>
        <tr>
            @if ($record->dok3)
                <td><img width="300px" src={{ url('storage/' . $record->dok3) }} alt="ttd"></td>
            @endif
            @if ($record->dok4)
                <td><img width="300px" src={{ url('storage/' . $record->dok4) }} alt="ttd"></td>
            @endif
        </tr>
    </table>
@else
@endif
