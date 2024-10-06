<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        table {
            font-family: Arial, sans-serif;
            margin-bottom: 10px;
            border: 1;
        }

        .page-break {
            page-break-after: always;
        }

        .judul {
            font-weight: bold;
            text-align: center;
        }

        .j-bab {
            font-weight: bold;
            /* width: 100%; */
        }

        .c1 {
            width: 4%;
        }

        .c2 {
            width: 38%;
        }

        .c3 {
            width: 58%;
        }

        .c4 {
            width: 30%;
        }

        .footer {
            text-align: right;
        }

        .np {
            font-weight: bold;
        }

        .c50 {
            width: 50%;
        }
    </style>
</head>
<!-- Header Tengah -->
<table width="100%" style="table-layout:fixed;">
    <tbody class="judul">
        <tr>
            <td>FORMULIR MODEL.A</td>
        </tr>
        <tr>
            <td>LAPORAN HASIL PENGAWASAN PEMILU</td>
        </tr>
        <tr>
            <td>NOMOR : {{ $record->nomor }}</td>
        </tr>
    </tbody>
</table>

<!-- {{-- I. Data Pengawas Pemilihan --}} -->
<table width="100%" style="table-layout:fixed;">
    <tr>
        <td class="c1"></td>
        <td class="c2"></td>
        <td class="c3"></td>
    </tr>
    <tr class="j-bab">
        <td class="c1">I. </td>
        <td colspan="2">Data Pengawas Pemilihan</td>

    </tr>
    <tr>
        <td></td>
        <td>a. Tahapan yang diawasi</td>
        <td>: {{ $record->Tahapan->name }}</td>
    </tr>
    <tr>
        <td></td>
        <td>b. Nama Pelaksana Tugas Pengawasan</td>
        <td>: {{ $record->user->name }}</td>
    </tr>
    <tr>
        <td></td>
        <td>c. Jabatan</td>
        <td>: PKD {{ $record->user->alamat }}</td>
    </tr>
    <tr>
        <td></td>
        <td>d. Nomor Surat Perintah Tugas</td>
        <td>: {{ $record->spt->kode }}</td>
    </tr>
    <tr>
        <td></td>
        <td>e. Alamat</td>
        <td>: {{ $record->user->alamat }} - Brondong - Lamongan</td>
    </tr>
</table>

{{-- II. Kegiatan Pengawasan --}}
<table width="100%" style="table-layout:fixed;">
    <tr>
        <td class="c1"></td>
        <td class="c2"></td>
        <td class="c3"></td>
    </tr>
    <tr class="j-bab">
        <td class="c1">II.</td>
        <td colspan="2">Kegiatan Pengawasan</td>
    </tr>
    <tr>
        <td class="c1"></td>
        <td class="c2">a. Bentuk</td>
        <td class="c3">: {{ $record->bentuk }}</td>
    </tr>
    <tr>
        <td class="c1"></td>
        <td class="c2">b. Tujuan</td>
        <td class="c3">: {{ $record->tujuan }}</td>
    </tr>
    <tr>
        <td class="c1"></td>
        <td class="c2">c. Sasaran</td>
        <td class="c3">: {{ $record->sasaran }}</td>
    </tr>
    <tr>
        <td class="c1"></td>
        <td class="c2">d. Waktu dan Tempat</td>
        <td class="c3">: {{ $record->waktem }}</td>
    </tr>
</table>

{{-- III.Uraian Hasil Pengawasan --}}
<table width="100%" style="table-layout:fixed;">
    <tr>
        <td class="c1"></td>
        <td class="c2"></td>
        <td class="c3"></td>
    </tr>
    <tr class="j-bab">
        <td>III.</td>
        <td colspan="2">Uraian Hasil Pengawasan</td>
    </tr>
    <td></td>
    <td colspan="2" align="justify">
        {{ $record->uraian }}
    </td>
</table>

{{-- IV. Informasi Dugaan Pelanggaran --}}
@if ($record->peristiwa_pel)
    <table width="100%" style="table-layout:fixed;">
        <tr>
            <td class="c1"></td>
            <td class="c1"></td>
            <td class="c1"> </td>
            <td class="c4"></td>
            <td class="c3"></td>
        </tr>
        <tr>
            <td class="j-bab">IV.</td>
            <td class="j-bab" colspan="3">Informasi Dugaan Pelanggaran</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>1.</td>
            <td colspan="4">Peristiwa</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="2">a. Peristiwa</td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="2">b. Tempat Kejadian</td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="2">c. Waktu Kejadian</td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="2">d. Pelaku</td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td>2.</td>
            <td colspan="3">Saksi - Saksi</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>a.</td>
            <td> nama</td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>alamat</td>
            <td>: ..................................</td>
        </tr>
        <tr></tr>
        <td></td>
        <td></td>
        <td>b.</td>
        <td> nama</td>
        <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>alamat</td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td>3.</td>
            <td colspan="3">Alat Bukti</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>a.</td>
            <td></td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>b.</td>
            <td></td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>c.</td>
            <td></td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td>4.</td>
            <td colspan="3">Barang Bukti</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>a.</td>
            <td></td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>b.</td>
            <td></td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>c.</td>
            <td></td>
            <td>: ..................................</td>
        </tr>

        <tr>
            <td></td>
            <td>5.</td>
            <td colspan="3">Uraian Singkat dugaan Pelanggaran</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="3" align="justify"></td>
        </tr>

        <tr></tr>
        <td></td>
        <td>6.</td>
        <td colspan="3">Fakta dan Keterangan</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="3" align="justify"></td>
        </tr>
        <tr></tr>
        <td></td>
        <td>7.</td>
        <td colspan="3">Analisa</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="3" align="justify"></td>
        </tr>
    </table>
@else
    <table width="100%" style="table-layout:fixed;">
        <tr>
            <td class="c1"></td>
            <td class="c1"></td>
            <td class="c1"> </td>
            <td class="c4"></td>
            <td class="c3"></td>
        </tr>
        <tr>
            <td class="j-bab">IV.</td>
            <td class="j-bab" colspan="3">Informasi Dugaan Pelanggaran</td>
            <td>: Tidak Ada</td>
        </tr>
    </table>
@endif


@if ($record->peserta_pemilu_seng)
    <table width="100%" style="table-layout:fixed;">
        <tr>
            <td class="c1"></td>
            <td class="c1"></td>
            <td class="c1"> </td>
            <td class="c4"></td>
            <td class="c3"></td>
        </tr>
        <tr>
            <td class="j-bab">V.</td>
            <td class="j-bab" colspan="3">Informasi Potensi Sengketan</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>1.</td>
            <td colspan="4">Peristiwa</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="2">a. Peserta Pemilu</td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="2">b. Tempat Kejadian</td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="2">c. Waktu Kejadian</td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td>2.</td>
            <td colspan="4">Objek Sengketa</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="2">a. Bentuk objek sengketa</td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="2">b. Identitas objek sengketa</td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="2">c. Hari/Tanggal dikeluarkan</td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="2">d. Kerugian langsung</td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td>2.</td>
            <td colspan="4">Objek Sengketa</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="2">a. Bentuk objek sengketa</td>
            <td>: ..................................</td>
        </tr>
        <tr>
            <td></td>
            <td>3. </td>
            <td colspan="3">Uraian singkat potensi sengketa</td>
            <!-- <td>: ..................................</td> -->
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="3" align="justify"></td>
        </tr>
    </table>
@else
    <table width="100%" style="table-layout:fixed;">
        <tr>
            <td class="c1"></td>
            <td class="c1"></td>
            <td class="c1"> </td>
            <td class="c4"></td>
            <td class="c3"></td>
        </tr>
        <tr>
            <td class="j-bab">V.</td>
            <td class="j-bab" colspan="3">Informasi Potensi Sengketan</td>
            <td>: Tidak Ada</td>
    </table>
@endif
{{-- V. Informasi Potensi Sengketan --}}


<br><br><br><br><br>
