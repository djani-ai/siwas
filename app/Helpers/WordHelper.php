<?php

namespace App\Helpers;

use App\Models\Lhp;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

class WordHelper
{
    //Dari AI
    public static function processWordTemplate($data, $templatePath, $outputPath)
    {
        $templateProcessor = new TemplateProcessor($templatePath);

        // Contoh mengisi template dengan data
        foreach ($data as $key => $value) {
            if (is_array($value) && isset($value['type']) && $value['type'] === 'image') {
                // Jika value adalah gambar, gunakan setImageValue
                $templateProcessor->setImageValue($key, $value['path'], $value['width'], $value['height']);
            } else {
                // Jika bukan gambar, gunakan setValue biasa
                $templateProcessor->setValue($key, $value);
            }
        }

        $templateProcessor->saveAs($outputPath);
        return $outputPath;
    }
    // public static function processWordTemplate(Lhp $lhp, $outputPath, $fileName)
    // {
    //     $bulanIndonesia = [
    //         "01" => "Januari",
    //         "02" => "Februari",
    //         "03" => "Maret",
    //         "04" => "April",
    //         "05" => "Mei",
    //         "06" => "Juni",
    //         "07" => "Juli",
    //         "08" => "Agustus",
    //         "09" => "September",
    //         "10" => "Oktober",
    //         "11" => "November",
    //         "12" => "Desember"
    //         ];
    //         list($tahun, $bulan, $tanggal) = explode("-", $lhp->tanggal_lap_seng);
    //     $namaBulan = $bulanIndonesia[$bulan];
    //     $tanggalDalamBahasaIndonesia = "$tanggal $namaBulan $tahun";
    //     $templateProcessor = new TemplateProcessor('word-template/lhp.docx');
    //     $templateProcessor->setValues([
    //         'noreg' => $lhp->nomor,
    //         'tahapan' => $lhp->tahapan->name,
    //         'namapkd' => $lhp->user->name,
    //         'jabatan' => 'PKD '. $lhp->user->kel->name,
    //         'nospt' => $lhp->spt->kode,
    //         'alamat' => $lhp->user->alamat,
    //         'bentuk' => $lhp->bentuk,
    //         'tujuan' => $lhp->tujuan,
    //         'sasaran' => $lhp->sasaran,
    //         'waktem' => $lhp->waktem,
    //         'uraian' => $lhp->uraian,
    //         'peristiwa_pel' => $lhp->peristiwa_pel,
    //         'tem_kejadian_pel' => $lhp->tem_kejadian_pel,
    //         'wak_kejadian_pel' => $lhp->wak_kejadian_pel,
    //         'pelaku_pel' => $lhp->pelaku_pel,
    //         'alamat_pel' => $lhp->alamat_pel,
    //         'nama_saksi_1' => $lhp->nama_saksi_1,
    //         'alamat_saksi' => $lhp->alamat_saksi_1,
    //         'nama_saksi_2' => $lhp->nama_saksi_2,
    //         'alamat_saksi_2' => $lhp->alamat_saksi_2,
    //         'alat_bukti_1' => $lhp->alat_bukti_1,
    //         'alat_bukti_2' => $lhp->alat_bukti_2,
    //         'alat_bukti_3' => $lhp->alat_bukti_3,
    //         'bb_1' => $lhp->bb_1,
    //         'bb_2' => $lhp->bb_2,
    //         'bb_3' => $lhp->bb_3,
    //         'uraian_pel' => $lhp->uraian_pel,
    //         'fakta_pel' => $lhp->fakta_pel,
    //         'analisa_pel' => $lhp->analisa_pel,
    //         'peserta_pemilu_seng' => $lhp->peserta_pemilu_seng,
    //         'tempat_seng' => $lhp->tempat_seng,
    //         'waktu_kejadian_seng' => $lhp->waktu_kejadian_seng,
    //         'bentuk_seng' => $lhp->bentuk_seng,
    //         'identitas_seng' => $lhp->identitas_seng,
    //         'hari_seng' => $lhp->hari_seng,
    //         'kerugian_seng' => $lhp->kerugian_seng,
    //         'uraian_seng' => $lhp->uraian_seng,
    //         'tanggal_lap_seng' => $tanggalDalamBahasaIndonesia
    //     ]);
    //     $templateProcessor->setImageValue('ttd', 'storage/'.$lhp->user->ttd);
    //     // Proses Dokumentasi
    //         if (($lhp->dok1)) {
    //             $templateProcessor->setImageValue('dok1', 'storage/'.$lhp->dok1);
    //         } else {
    //             $templateProcessor->setValue('dok1', '');
    //         }
    //         if (($lhp->dok2)) {
    //             $templateProcessor->setImageValue('dok2', 'storage/'.$lhp->dok2);
    //         } else {
    //             $templateProcessor->setValue('dok2', '');
    //         }
    //         if (($lhp->dok3)) {
    //             $templateProcessor->setImageValue('dok3', 'storage/'.$lhp->dok3);
    //         } else {
    //             $templateProcessor->setValue('dok3', '');
    //         }
    //         if (($lhp->dok4)) {
    //             $templateProcessor->setImageValue('dok4', 'storage/'.$lhp->dok4);
    //         } else {
    //             $templateProcessor->setValue('dok4', '');
    //         }
    //     $templateProcessor->saveAs($outputPath, $fileName);
    //     return $outputPath;
    // }
}
