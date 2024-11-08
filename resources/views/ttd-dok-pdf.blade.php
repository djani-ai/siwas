{{-- Tanda Tangan --}}
<table class="footer" width="100%" style="table-layout: fixed;">
    <tr>
        <td>Brondong, {{ date('d F Y', strtotime($record->tanggal_lap_seng)) }}</td>
    </tr>
    <tr>
        <td><img width="250px" src={{ 'storage/' . $record->user->ttd }} alt="ttd"></td>
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
        <br>
        <tr>
            <td colspan="2" class="judul">DOKUMENTASI</td>
            <td></td>
        </tr>
        <br><br><br>
        <tr>
            <td><img width="300px" src={{ 'storage/' . $record->dok1 }} alt="ttd"></td>
            @if ($record->dok2)
                <td><img width="300px" src={{ 'storage/' . $record->dok2 }} alt="ttd"></td>
            @endif
        </tr>
        <tr>
            @if ($record->dok3)
                <td><img width="300px" src={{ 'storage/' . $record->dok3 }} alt="ttd"></td>
            @endif
            @if ($record->dok4)
                <td><img width="300px" src={{ 'storage/' . $record->dok4 }} alt="ttd"></td>
            @endif
        </tr>
    </table>
@else
@endif
