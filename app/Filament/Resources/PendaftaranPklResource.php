<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranPklResource\Pages;
use App\Filament\Resources\PendaftaranPklResource\RelationManagers;
use App\Models\PendaftaranPkl;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class PendaftaranPklResource extends Resource
{
    protected static ?string $model = PendaftaranPkl::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('siswa_id')
                    ->relationship('siswa', 'nama')
                    ->label('Nama Siswa')
                    ->required()
                    ->searchable()
                    ->preload(),
                    
                Select::make('lowongan_pkl_id')
                    ->relationship('lowonganPkl', 'id') // Sementara pakai ID Lowongan
                    ->label('ID Lowongan')
                    ->required(),
                    
                TextInput::make('nilai_pra_pkl')
                    ->label('Nilai Pra-PKL')
                    ->numeric(),
                    
                Select::make('status')
                    ->options([
                        'Menunggu' => 'Menunggu',
                        'Disetujui' => 'Disetujui',
                        'Ditolak' => 'Ditolak',
                    ])
                    ->default('Menunggu')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa.nama')->label('Siswa')->searchable(),
                TextColumn::make('lowonganPkl.id')->label('ID Lowongan'),
                TextColumn::make('nilai_pra_pkl')->label('Nilai'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Menunggu' => 'warning',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                    }),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPendaftaranPkls::route('/'),
            'create' => Pages\CreatePendaftaranPkl::route('/create'),
            'edit' => Pages\EditPendaftaranPkl::route('/{record}/edit'),
        ];
    }
}
