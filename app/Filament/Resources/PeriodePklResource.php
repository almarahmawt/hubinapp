<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeriodePklResource\Pages;
use App\Filament\Resources\PeriodePklResource\RelationManagers;
use App\Models\PeriodePkl;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;

class PeriodePklResource extends Resource
{
    protected static ?string $model = PeriodePkl::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Periode (Contoh: Gelombang 1)')
                    ->required(),
                TextInput::make('tahun_ajaran')
                    ->label('Tahun Ajaran (Contoh: 2026/2027)')
                    ->required(),
                DatePicker::make('tanggal_mulai')
                    ->required(),
                DatePicker::make('tanggal_selesai')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->searchable(),
                TextColumn::make('tahun_ajaran')->searchable(),
                TextColumn::make('tanggal_mulai')->date()->sortable(),
                TextColumn::make('tanggal_selesai')->date()->sortable(),
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
            'index' => Pages\ListPeriodePkls::route('/'),
            'create' => Pages\CreatePeriodePkl::route('/create'),
            'edit' => Pages\EditPeriodePkl::route('/{record}/edit'),
        ];
    }
}
