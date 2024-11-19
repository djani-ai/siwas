<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlatKerjaResource\Pages;
use App\Models\AlatKerja;
use EightyNine\ExcelImport\Facades\ExcelImportAction;
// use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;



class AlatKerjaResource extends Resource
{
    protected static ?string $model = AlatKerja::class;
    protected static ?string $label = "Alat Kerja";
    protected static ?int $navigationSort = 2;
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
                Select::make('tps_id')
                    ->relationship('tps', 'name')
                    ->label('TPS')
                    ->required(),
            ]);
    }


    public static function table(Table $table,): Table
    {

        $role = auth()->user()->roles->pluck('id');
        if (($role->contains(1))) {
            return $table
                // ->headerActions([
                //     CreateAction::make('New')
                // ])
                ->columns([
                    Stack::make([
                        IconColumn::make('icon')
                            ->label('icon'),
                        // ->icon(fn(string $state): string => match ($state) {
                        //     // 'heroicon-o-check-circle',
                        //     'draft' => 'heroicon-o-pencil',
                        //     //     'reviewing' => 'heroicon-o-clock',
                        //     //     'published' => 'heroicon-o-check-circle',
                        // }),
                        TextColumn::make('name')
                            ->label('Nama alat Kerja'),
                        TextColumn::make('description')
                            ->limit(20)
                            ->label('Deskripsi'),
                        // TextColumn::make('link'),
                        TextColumn::make('kel.name')
                            ->label('Desa/Kelurahan'),
                        TextColumn::make('tps.name')
                            ->label('TPS'),
                    ]),
                ])
                ->contentGrid([
                    'md' => 2,
                    'xl' => 3,
                ])
                // ->columns([
                //     IconColumn::make('icon')
                //     ->label('icon'),
                //     TextColumn::make('name')
                //     ->label('Nama alat Kerja'),
                //     TextColumn::make('description')
                //     ->limit(20)
                //     ->label('Deskripsi'),
                //     TextColumn::make('link'),
                //     TextColumn::make('kel.name')
                //     ->label('Desa/Kelurahan'),
                // ])
                ->filters([
                    SelectFilter::make('TPS')
                        ->relationship('tps', 'name'),
                    SelectFilter::make('Desa/Kelurahan')
                        ->relationship('kel', 'name')
                ])
                ->actions([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Hajar')
                        ->icon('heroicon-o-cursor-arrow-ripple')
                        ->url(fn(AlatKerja $record): string => $record->link)
                        ->openUrlInNewTab(),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                        Tables\Actions\DeleteBulkAction::make(),
                    ]),
                ]);
        } else {
            $kel = Auth::getUser()->kel_id;
            return $table
                ->reorderable('sort')
                ->paginatedWhileReordering()
                // ->columns([
                //     TextColumn::make('name')
                //         ->label('')
                //         ->description(fn(AlatKerja $record): string => $record->description, position: 'under')
                //         ->wrap()
                //         ->limit(30),
                // ])
                ->columns([
                    Stack::make([
                        // IconColumn::make('icon')
                        //     ->label('icon'),
                        TextColumn::make('name')
                            ->label('Nama alat Kerja'),
                        TextColumn::make('description')
                            ->label('Deskripsi'),
                        TextColumn::make('kel.name')
                            ->label('Desa/Kelurahan'),
                        TextColumn::make('tps.name')
                            ->label('TPS'),
                    ]),
                ])
                ->contentGrid([
                    'md' => 2,
                    'xl' => 2,
                ])
                ->paginated(false)
                ->filters([
                    SelectFilter::make('TPS')
                        ->relationship('tps', 'name', fn(Builder $query) => $query->where('kel_id', $kel)),
                    SelectFilter::make('Desa/Kelurahan')
                        ->relationship('kel', 'name', fn(Builder $query) => $query->where('id', $kel))
                ])
                ->actions([
                    Tables\Actions\Action::make('Hajar')
                        ->icon('heroicon-o-cursor-arrow-ripple')
                        ->url(fn(AlatKerja $record): string => $record->link)
                        ->openUrlInNewTab(),
                ])
                ->recordUrl(fn(AlatKerja $record): string => $record->link)
                ->defaultSort('sort');
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAlatKerjas::route('/'),
        ];
    }
}
