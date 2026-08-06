<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KompetensiKeahlianResource\Pages;
use App\Models\KompetensiKeahlian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// Tambahan untuk input dan tabel
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class KompetensiKeahlianResource extends Resource
{
    protected static ?string $model = KompetensiKeahlian::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Jurusan / Kompetensi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Kompetensi Keahlian (Contoh: Rekayasa Perangkat Lunak)')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('kode')
                    ->label('Kode Jurusan (Contoh: RPL)')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Jurusan')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListKompetensiKeahlians::route('/'),
            'create' => Pages\CreateKompetensiKeahlian::route('/create'),
            'edit' => Pages\EditKompetensiKeahlian::route('/{record}/edit'),
        ];
    }
}