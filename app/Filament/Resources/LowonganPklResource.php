<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LowonganPklResource\Pages;
use App\Filament\Resources\LowonganPklResource\RelationManagers;
use App\Models\LowonganPkl;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class LowonganPklResource extends Resource
{
    protected static ?string $model = LowonganPkl::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('industri_id')
                    ->relationship('industri', 'nama')
                    ->label('Perusahaan')
                    ->required()
                    ->preload(),
                Select::make('periode_id')
                    ->relationship('periode', 'nama')
                    ->label('Untuk Periode PKL')
                    ->required()
                    ->preload(),
                TextInput::make('kuota')
                    ->label('Kuota Siswa')
                    ->numeric()
                    ->required(),
                Textarea::make('syarat_khusus')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('industri.nama')->label('Perusahaan')->searchable(),
                TextColumn::make('periode.nama')->label('Periode'),
                TextColumn::make('kuota')->badge(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListLowonganPkls::route('/'),
            'create' => Pages\CreateLowonganPkl::route('/create'),
            'edit' => Pages\EditLowonganPkl::route('/{record}/edit'),
        ];
    }
}
