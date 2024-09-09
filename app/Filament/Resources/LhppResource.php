<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LhppResource\Pages;
use App\Filament\Resources\LhppResource\RelationManagers;
use App\Helpers\PDFHelper;
use App\Models\Lhpp;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;

class LhppResource extends Resource
{
    protected static ?string $model = Lhpp::class;
    protected static ?string $navigationGroup = 'Form A';
    protected static ?string $navigationLabel = 'Form A LHP Panwas';
    protected static ?string $pluralModelLabel = 'Form A Panwas';
    protected static ?string $slug = 'form-a-lhpp';
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    public static function form(Form $form): Form
    {
        $maxno = Lhpp::max('no')+1;
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
        $kodebln =date('d').'/'. $bulanRomawi[$bulan] . '/' . date('Y'); // Membentuk kode bulan dengan format Romawi dan tahun
        $noreg = '/LHP/PM.01.02/JI-11.07.'.'/' . $kodebln ;
        // dd($maxno);
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
                                ->default($maxno)
                                ->live()
                                ->afterStateUpdated(fn (Set $set, ?int $state) => $set('nomor', str_pad($state, 3, '0', STR_PAD_LEFT) . $noreg))
                                ->numeric(),

                            Forms\Components\TextInput::make('nomor')
                                ->label('Nomor Surat Form A')
                                ->default(fn (Get $get) => (str_pad($get('no'), 3, '0', STR_PAD_LEFT) . $noreg))
                                ->required()
                                ->maxLength(255),
                                ]),
                        Fieldset::make('Uraian')
                        ->schema([
                            Forms\Components\Select::make('pelaksana')
                                ->label('Nama Petugas Pengawasan')
                                ->options([
                                    'MOH. SYIFAUN' => 'MOH. SYIFAUN',
                                    'MOH. MARZUQI' => 'MOH. MARZUQI',
                                    'YODI KURNIAWAN' => 'YODI KURNIAWAN',
                                ])
                                ->required()
                                ->selectablePlaceholder(true),
                            Forms\Components\Select::make('tahapan_id')
                                ->required()
                                ->relationship('tahapan', 'name'),
                            Forms\Components\TextInput::make('spt')
                                ->label('Surat Perintah Tugas')
                                ->required(),
                            Forms\Components\Select::make('bentuk')
                                ->label('Bentuk Pengawasan')
                                ->options([
                                    'Pengawasan Langsung' => 'Pengawasan Langsung',
                                    'Pengawasan Tidak Langsung' => 'Pengawasan Tidak Langsung'])
                                ->required(),
                            Forms\Components\TextInput::make('tujuan')
                                ->label('Tujuan')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('sasaran')
                                ->label('Sasaran')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('waktem')
                                ->label('Waktu & Tempat Pelaksanaan')
                                ->columnSpanFull()
                                ->required()
                                ->maxLength(255),
                            Forms\Components\RichEditor::make('uraian')
                                ->label('Uraian Hasil Pengawasan')
                                ->required()
                                ->maxLength(65535)
                                ->columnSpanFull(),
                            Forms\Components\Toggle::make('pelanggaran')
                                ->label('Pelanggaran ?')
                                ->required(),
                            Forms\Components\Toggle::make('sengketa')
                                ->label('Sengketa?')
                                ->required(),
                            ]),
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
                Tables\Columns\TextColumn::make('pelaksana')
                    ->label('Petugas'),
                Tables\Columns\TextColumn::make('spt')
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->color('success')
                    ->icon('heroicon-s-arrow-down-tray')
                    ->action(function (Lhpp $record)
                    {
                        $namafile = str_replace("/", "-", $record->nomor);
                        return response()->streamDownload(function () use ($record) {
                            echo Pdf::loadHtml(
                                Blade::render('pdf', ['record' => $record])
                            )->setPaper('folio')->save('DATA-FORM-A/' . str_replace("/", "-", $record->nomor) . '.pdf')->stream();
                        }, $namafile . '.pdf');
                    }),
                    // ->save('/path-to/my_stored_file.pdf')->stream('download.pdf');

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListLhpps::route('/'),
            'create' => Pages\CreateLhpp::route('/create'),
            'view' => Pages\ViewLhpp::route('/{record}'),
            'edit' => Pages\EditLhpp::route('/{record}/edit'),
        ];
    }
}
