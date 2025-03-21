<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LhpResource\Pages;
use App\Models\Lhp;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\TemplateProcessor;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Support\Facades\Blade;
use App\Helpers\PdfHelper;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class LhpResource extends Resource
{
    protected static ?string $model = Lhp::class;
    protected static ?string $navigationGroup = 'Form A';
    protected static ?string $navigationLabel = 'Form A LHP';
    protected static ?string $pluralModelLabel = 'Form A';
    protected static ?string $slug = 'form-a-lhp';
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';
    // protected static string $view = 'filament.resources.view-lhpp';

    public static function form(Form $form): Form
    {
        // $role = Auth::getUser()->roles->pluck('id');
        // dd($role);
        $maxValuepkd = Lhp::where('kel_id', Auth::getUser()->kel_id)->max('no') + 1;
        $kodepkd = Auth::getUser()->kel->kode;
        $bulanRomawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        $bulan = intval(date('m')); // Mengambil bulan saat ini dan mengkonversinya menjadi integer
        $kodebln = date('d') . '/' . $bulanRomawi[$bulan] . '/' . date('Y'); // Membentuk kode bulan dengan format Romawi dan tahun
        $noreg = '/LHP/PM.01.02/JI-11.07.' . $kodepkd . '/' . $kodebln;
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Uraian Pengawasan')
                        ->icon('heroicon-m-document-magnifying-glass')
                        ->schema([
                            Fieldset::make('Nomor Registrasi')
                                ->schema([
                                    Forms\Components\TextInput::make('no')
                                        ->label('No Urut')
                                        ->default($maxValuepkd)
                                        ->live()
                                        ->afterStateUpdated(fn(Set $set, ?int $state) => $set('nomor'))
                                        ->numeric(),
                                    // ->unique(),
                                    Forms\Components\TextInput::make('nomor')
                                        ->label('Nomor Surat Form A')
                                        ->default(fn(Get $get) => (str_pad($get('no'), 3, '0', STR_PAD_LEFT) . $noreg))
                                        // ->unique()
                                        ->required()
                                        ->maxLength(255),
                                ]),
                            Fieldset::make('Uraian')
                                ->schema([
                                    Forms\Components\Select::make('user_id')
                                        ->relationship('user', 'name')
                                        ->label('Nama Petugas Pengawasan')
                                        ->options([
                                            Auth::getUser()->id => Auth::getUser()->name,
                                        ])
                                        ->default(Auth::getUser()->id)
                                        ->required()
                                        ->selectablePlaceholder(false),
                                    Forms\Components\Select::make('kec_id')
                                        ->relationship('kec', 'name')
                                        ->label('Kecamatan')
                                        ->options([
                                            Auth::getUser()->kec->id => Auth::getUser()->kec->name,
                                        ])
                                        ->default(Auth::getUser()->kec->id)
                                        ->required()
                                        ->selectablePlaceholder(false),
                                    Forms\Components\Select::make('kel_id')
                                        ->relationship('kel', 'name')
                                        ->label('Desa/Kelurahan')
                                        ->options([
                                            Auth::getUser()->kel->id => Auth::getUser()->kel->name,
                                        ])
                                        ->default(Auth::getUser()->kel->id)
                                        ->required()
                                        ->selectablePlaceholder(false),
                                    Forms\Components\Select::make('tahapan_id')
                                        ->required()
                                        ->relationship('tahapan', 'name'),
                                    Forms\Components\Select::make('spt_id')
                                        ->label('Surat Perintah Tugas')
                                        ->required()
                                        ->relationship('spt', 'nama'),
                                    Forms\Components\TextInput::make('bentuk')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('tujuan')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('sasaran')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('waktem')
                                        ->label('Waktu dan Tempat')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\DatePicker::make('tanggal_lap_seng')
                                        ->label('Tanggal Pengawasan/Pelaporan')
                                        ->required(),
                                    Forms\Components\Textarea::make('uraian')
                                        ->label('Uraian Singkat Hasil Pengawasan ')
                                        ->required()
                                        ->maxLength(65535)
                                        ->rows('8')
                                        ->columnSpanFull(),
                                ])
                        ]),
                    Wizard\Step::make('Dugaan Pelanggaran')
                        ->icon('heroicon-m-bug-ant')
                        ->schema([
                            Forms\Components\TextInput::make('peristiwa_pel')
                                ->label('Peristiwa Pelanggaran')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('tem_kejadian_pel')
                                ->label('Tempat Kejadian Pelanggaran')
                                ->maxLength(255),
                            Forms\Components\DatePicker::make('wak_kejadian_pel')
                                ->label('Waktu Kejadian Pelanggaran')
                                ->format('dd-mm-YYYY'),
                            Forms\Components\TextInput::make('pelaku_pel')
                                ->label('Pelaku Pelanggaran')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('alamat_pel')
                                ->label('Alamat Pelanggaran')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('nama_saksi_1')
                                ->label('Nama Saksi 1')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('alamat_saksi_1')
                                ->label('Alamat Saksi 1')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('nama_saksi_2')
                                ->label('Nama Saksi 2')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('alamat_saksi_2')
                                ->label('Alamat Saksi 2')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('alat_bukti_1')
                                ->label('Alat Bukti 1')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('alat_bukti_2')
                                ->label('Alat Bukti 2')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('alat_bukti_3')
                                ->label('Alat Bukti 3')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('bb_1')
                                ->label('Barang Bukti 1')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('bb_2')
                                ->label('Barang Bukti 2')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('bb_3')
                                ->label('Barang Bukti 3')
                                ->maxLength(255),
                            Forms\Components\Textarea::make('uraian_pel')
                                ->label('Uraian Pelanggaran')
                                ->maxLength(65535)
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('fakta_pel')
                                ->label('Fakta & Keterangan Pelanggaran')
                                ->maxLength(65535)
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('analisa_pel')
                                ->label('Analisa Pelanggaran')
                                ->maxLength(65535)
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                    Wizard\Step::make('Informasi Sengketa')
                        ->icon('heroicon-m-hand-raised')
                        ->schema([
                            Forms\Components\TextInput::make('peserta_pemilu_seng')
                                ->label('Peserta Pemilu Sengketa')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('tempat_seng')
                                ->label('Tempat Kejadian Sengketa')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('waktu_kejadian_seng')
                                ->label('Waktu Kejadian Sengketa')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('bentuk_seng')
                                ->label('Bentuk Objek sengketa')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('identitas_seng')
                                ->label('Identitas Objek Sengketa')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('hari_seng')
                                ->label('Hari/tanggal Dikeluarkan')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('kerugian_seng')
                                ->label('Kerugian langsung')
                                ->maxLength(255),
                            Forms\Components\Textarea::make('uraian_seng')
                                ->label('Uraian singkat potensi sengketa')
                                ->maxLength(65535)
                                ->columnSpanFull(),
                        ])->columns(3),
                    Wizard\Step::make('Dokumentasi')
                        ->icon('heroicon-m-camera')
                        ->schema([
                            Forms\Components\FileUpload::make('dok1')
                                ->image()
                                ->label('Dokumentasi 1')
                                ->imageResizeTargetWidth('512')
                                ->optimize('jpg'),
                            Forms\Components\FileUpload::make('dok2')
                                ->image()
                                ->label('Dokumentasi 2')
                                ->imageResizeTargetWidth('512')
                                ->optimize('jpg'),
                            Forms\Components\FileUpload::make('dok3')
                                ->image()
                                ->label('Dokumentasi 3')
                                ->imageResizeTargetWidth('512')
                                ->optimize('jpg'),
                            Forms\Components\FileUpload::make('dok4')
                                ->image()
                                ->label('Dokumentasi 4')
                                ->imageResizeTargetWidth('512')
                                ->optimize('jpg'),
                        ])->columns(2),
                ])->columnSpanFull()
                    ->skippable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nomor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahapan.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kel.name')
                    ->label('Desa/Kelurahan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Petugas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('spt.kode')
                    ->label('Nomor SPT')
                    ->limit(20)
                    ->sortable(),
                Tables\Columns\TextColumn::make('bentuk')
                    ->limit(10)
                    ->searchable(),
                Tables\Columns\TextColumn::make('tujuan')
                    ->limit(15)
                    ->searchable(),
                Tables\Columns\TextColumn::make('sasaran')
                    ->limit(15)
                    ->searchable(),
                Tables\Columns\TextColumn::make('waktem')
                    ->label('Waktu & Tempat Kejadian')
                    ->wrapHeader()
                    ->limit(20)
                    ->searchable(),
                Tables\Columns\TextColumn::make('uraian')
                    ->wrapHeader()
                    ->limit(20)
                    ->label('Uraian Kejadian')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\ImageColumn::make('dok1')
                    ->label('Doc-1'),
                Tables\Columns\ImageColumn::make('dok2')
                    ->label('Doc-2'),
                Tables\Columns\ImageColumn::make('dok3')
                    ->label('Doc-3'),
                Tables\Columns\ImageColumn::make('dok4')
                    ->label('Doc-4'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('no', 'desc')
            ->defaultPaginationPageOption(25)
            ->searchPlaceholder('Pencarian')
            ->striped()
            ->filters([
                SelectFilter::make('Tahapan')
                    ->relationship('tahapan', 'name'),
                SelectFilter::make('Desa/Kelurahan')
                    ->relationship('kel', 'name')
            ])
            // Header di dalam Table
            // ->headerActions([

            //     ExportAction::make()->exports([
            //         ExcelExport::make('table')->fromTable(),
            //         ExcelExport::make('form')->fromForm(),
            //         ExcelExport::make('Model')->fromModel()
            //     ]),
            // ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
                Tables\Actions\EditAction::make()
                    ->label('Edit'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
                // EXPORT
                Tables\Actions\Action::make('Word')
                    ->icon('heroicon-o-printer')
                    ->action(function (Lhp $lhp, array $data) {
                        // Penanggalan Indonesia
                        $bulanIndonesia = [
                            "01" => "Januari",
                            "02" => "Februari",
                            "03" => "Maret",
                            "04" => "April",
                            "05" => "Mei",
                            "06" => "Juni",
                            "07" => "Juli",
                            "08" => "Agustus",
                            "09" => "September",
                            "10" => "Oktober",
                            "11" => "November",
                            "12" => "Desember"
                        ];
                        list($tahun, $bulan, $tanggal) = explode("-", $lhp->tanggal_lap_seng);
                        $namaBulan = $bulanIndonesia[$bulan];
                        $tanggalDalamBahasaIndonesia = "$tanggal $namaBulan $tahun";
                        // $section = PhpWord
                        // Prosessing INPUT
                        $templateProcessor = new TemplateProcessor('word-template/lhp.docx');
                        $templateProcessor->setValues([
                            'noreg' => $lhp->nomor,
                            'tahapan' => $lhp->tahapan->name,
                            'namapkd' => $lhp->user->name,
                            'jabatan' => 'PKD ' . $lhp->user->kel->name,
                            'nospt' => $lhp->spt->kode,
                            'alamat' => $lhp->user->alamat,
                            'bentuk' => $lhp->bentuk,
                            'tujuan' => $lhp->tujuan,
                            'sasaran' => $lhp->sasaran,
                            'waktem' => $lhp->waktem,
                            'uraian' => $lhp->uraian,
                            'peristiwa_pel' => $lhp->peristiwa_pel,
                            'tem_kejadian_pel' => $lhp->tem_kejadian_pel,
                            'wak_kejadian_pel' => $lhp->wak_kejadian_pel,
                            'pelaku_pel' => $lhp->pelaku_pel,
                            'alamat_pel' => $lhp->alamat_pel,
                            'nama_saksi_1' => $lhp->nama_saksi_1,
                            'alamat_saksi' => $lhp->alamat_saksi_1,
                            'nama_saksi_2' => $lhp->nama_saksi_2,
                            'alamat_saksi_2' => $lhp->alamat_saksi_2,
                            'alat_bukti_1' => $lhp->alat_bukti_1,
                            'alat_bukti_2' => $lhp->alat_bukti_2,
                            'alat_bukti_3' => $lhp->alat_bukti_3,
                            'bb_1' => $lhp->bb_1,
                            'bb_2' => $lhp->bb_2,
                            'bb_3' => $lhp->bb_3,
                            'uraian_pel' => $lhp->uraian_pel,
                            'fakta_pel' => $lhp->fakta_pel,
                            'analisa_pel' => $lhp->analisa_pel,
                            'peserta_pemilu_seng' => $lhp->peserta_pemilu_seng,
                            'tempat_seng' => $lhp->tempat_seng,
                            'waktu_kejadian_seng' => $lhp->waktu_kejadian_seng,
                            'bentuk_seng' => $lhp->bentuk_seng,
                            'identitas_seng' => $lhp->identitas_seng,
                            'hari_seng' => $lhp->hari_seng,
                            'kerugian_seng' => $lhp->kerugian_seng,
                            'uraian_seng' => $lhp->uraian_seng,
                            'tanggal_lap_seng' => $tanggalDalamBahasaIndonesia
                        ]);
                        $templateProcessor->setImageValue('ttd', 'storage/' . $lhp->user->ttd);
                        // Proses Dokumentasi
                        if (($lhp->dok1)) {
                            $templateProcessor->setImageValue('dok1', 'storage/' . $lhp->dok1);
                        } else {
                            $templateProcessor->setValue('dok1', '');
                        }
                        if (($lhp->dok2)) {
                            $templateProcessor->setImageValue('dok2', 'storage/' . $lhp->dok2);
                        } else {
                            $templateProcessor->setValue('dok2', '');
                        }
                        if (($lhp->dok3)) {
                            $templateProcessor->setImageValue('dok3', 'storage/' . $lhp->dok3);
                        } else {
                            $templateProcessor->setValue('dok3', '');
                        }
                        if (($lhp->dok4)) {
                            $templateProcessor->setImageValue('dok4', 'storage/' . $lhp->dok4);
                        } else {
                            $templateProcessor->setValue('dok4', '');
                        }
                        $fileName = str_replace("/", "-", $lhp->nomor);
                        $templateProcessor->saveAs('DATA-FORM-A/' . $lhp->kel->name . '/' . $fileName . '.docx');
                        return response()
                            ->download('DATA-FORM-A/' . $lhp->kel->name . '/' . $fileName . '.docx')
                            ->deleteFileAfterSend(false);
                    }),
                Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->color('success')
                    ->icon('heroicon-s-arrow-down-tray')
                    ->action(
                        // function (Lhp $record) {
                        //     $namafile = str_replace("/", "-", $record->nomor);
                        //     return PdfHelper::generatePdfContent('cetakpdflhp', ['record' => $record], $namafile);
                        // }
                        function (Lhp $record) {
                            $namafile = str_replace("/", "-", $record->nomor);
                            return response()->stream(function () use ($record) {
                                // Menggunakan DOMPDF untuk memuat HTML dan menghasilkan PDF
                                $pdf = Pdf::loadHtml(
                                    Blade::render('cetakpdflhp', ['record' => $record])
                                )->setPaper('Folio');
                                // Render PDF ke output
                                echo $pdf->output();
                            }, 200, [
                                'Content-Type' => 'application/pdf',
                                'Content-Disposition' => 'inline; filename="' . $namafile . '.pdf"',
                            ]);
                        }
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('download_pdf_zip')
                        ->label('Download PDF di ZIP_NO')
                        ->color('success')
                        ->icon('heroicon-s-arrow-down-tray')
                        ->action(function (Collection $records) {
                            // Tentukan direktori temporary untuk menyimpan file PDF
                            $tempDir = storage_path('app/temp_pdfs');
                            Storage::makeDirectory('temp_pdfs');

                            // Buat PDF untuk setiap record dan simpan di temporary directory
                            foreach ($records as $record) {
                                $filename = str_replace("/", "-", $record->nomor) . '.pdf';
                                $filePath = $tempDir . '/' . $filename;

                                // Generate PDF content and save to file
                                $pdfContent = PdfHelper::generatePdfContent('cetakpdflhp', ['record' => $record]);
                                file_put_contents($filePath, $pdfContent);
                            }

                            // Buat file ZIP dari semua PDF yang ada di temporary directory
                            $zipFilename = 'FORM-A-' . now()->format('Y-m-d_His') . '.zip';
                            $zipFilePath = storage_path('app/' . $zipFilename);

                            $zip = new ZipArchive();
                            if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
                                $files = Storage::files('temp_pdfs');
                                foreach ($files as $file) {
                                    $zip->addFile(storage_path('app/' . $file), basename($file));
                                }
                                $zip->close();
                            }

                            // Hapus semua file PDF di direktori temporary
                            Storage::deleteDirectory('temp_pdfs');

                            // Kirim file ZIP ke pengguna
                            return response()->download($zipFilePath)->deleteFileAfterSend(true);
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),

                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLhps::route('/'),
            'create' => Pages\CreateLhp::route('/create'),
            'edit' => Pages\EditLhp::route('/{record}/edit'),
            'view' => Pages\ViewLhp::route('/{record}/view'),
        ];
    }
}
