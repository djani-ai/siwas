<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InfoResource\Pages;
use App\Models\Info;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class InfoResource extends Resource
{
    protected static ?string $model = Info::class;
    protected static ?string $navigationGroup = 'Alat Kerja';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $navigationLabel = 'Info';
    protected static ?string $pluralModelLabel = 'Info';
    protected static ?string $slug = 'info';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    // ->toolbarButtons([
                    //     'bold',
                    //     'italic',
                    //     'link',
                    //     'bulletList',
                    //     'orderedList',
                    //     'image', // Menambahkan tombol upload gambar
                    // ])
                    ->fileAttachmentsDisk('public2') // Disk untuk menyimpan file
                    // ->fileAttachmentsVisibility('public') // Akses file publik
                    ->fileAttachmentsDirectory('uploads') // Direktori penyimpanan file
                    //->fileAttachmentsRules(['image', 'max:1024']) // Validasi file (hanya gambar, max 1MB)
                    //     ->label('Deskripsi')
                    //     ->fileAttachmentsDisk('public') // Disk untuk menyimpan file
                    //     ->fileAttachmentsVisibility('public') // Akses file publik
                    //     ->fileAttachmentsDirectory('attachFiles') // Direktori penyimpanan file
                    //     ->required()
                    //     // ->maxLength(65535)
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')
                    ->label('')
                    ->html()
                    // ->markdown()
                    ->wrap()

            ])
            ->paginated(false)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageInfos::route('/'),
        ];
    }
}