<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlatKerjaResource\Pages;
use App\Models\AlatKerja;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class AlatKerjaResource extends Resource
{
    protected static ?string $model = AlatKerja::class;
    protected static ?string $label = "Alat Kerja";
    protected static ?string $navigationGroup = 'Alat Kerja';
    protected static ?string $navigationLabel = 'Alat Kerja';
    protected static ?string $pluralModelLabel = 'Alat Kerja';
    protected static ?string $slug = 'alat-kerja';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                TextInput::make('icon')
                    ->label('icon')
                    ->required()
                    ->maxLength(255),
                TextInput::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->maxLength(255),
                TextInput::make('link')
                    ->label('Link URL')
                    ->required('url')
                    ->maxLength(255),
                Select::make('kel_id')
                    ->relationship('kel', 'name')
                    ->label('Desa')
                    ->required(),
            ]);
    }


    public static function table(Table $table,): Table
    {

        $role = auth()->user()->roles->pluck('id');
        if (($role->contains(1))) {
            return $table
            ->headerActions([
                CreateAction::make('New')])
            ->columns([
                IconColumn::make('icon')
                ->label('icon'),
                TextColumn::make('name')
                ->label('Nama alat Kerja'),
                TextColumn::make('description')
                ->label('Deskripsi'),
                TextColumn::make('link'),
                TextColumn::make('kel.name')
                ->label('Desa/Kelurahan'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Hajar')
                ->icon('heroicon-o-cursor-arrow-ripple')
                ->url(fn (AlatKerja $record): string => $record->link)
                ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
        } else {
            return $table
            ->columns([
                TextColumn::make('name')
                ->label('')
                ->description(fn (AlatKerja $record): string => $record->description, position: 'above'),
            ])
            ->paginated(false)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Hajar')
                ->icon('heroicon-o-cursor-arrow-ripple')
                ->url(fn (AlatKerja $record): string => $record->link)
                ->openUrlInNewTab(),
            ])
            ->recordUrl(fn (AlatKerja $record): string => $record->link)
            ;
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAlatKerjas::route('/'),
        ];
    }
}
