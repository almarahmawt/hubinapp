<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Filament\Resources\SiswaResource\RelationManagers;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kompetensi_id')
                    ->relationship('kompetensi', 'nama')
                    ->label('Jurusan')
                    ->required()
                    ->preload()
                    ->searchable(),

                Select::make('kelas_id')
                    ->relationship('kelas', 'nama')
                    ->label('Kelas')
                    ->required()
                    ->preload()
                    ->searchable(),

                TextInput::make('nis')
                    ->label('NIS')
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('nama')
                    ->label('Nama Lengkap Siswa')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable(),
                    
                TextColumn::make('nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->sortable(),
                    
                TextColumn::make('kompetensi.nama')
                    ->label('Jurusan')
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
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }
}
