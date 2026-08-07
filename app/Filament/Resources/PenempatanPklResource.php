<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenempatanPklResource\Pages;
use App\Filament\Resources\PenempatanPklResource\RelationManagers;
use App\Models\PenempatanPkl;
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

class PenempatanPklResource extends Resource
{
    protected static ?string $model = PenempatanPkl::class;

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
                    
                Select::make('industri_id')
                    ->relationship('industri', 'nama')
                    ->label('Industri (Tempat PKL)')
                    ->required()
                    ->searchable()
                    ->preload(),
                    
                Select::make('guru_id')
                    ->relationship('guru', 'nama')
                    ->label('Guru Pembimbing')
                    ->searchable()
                    ->preload(),
                    
                TextInput::make('jawaban_industri')
                    ->label('Keterangan / Surat Balasan')
                    ->maxLength(255),
                    
                Select::make('status')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Selesai' => 'Selesai',
                        'Batal' => 'Batal',
                    ])
                    ->default('Aktif')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa.nama')->label('Siswa')->searchable(),
                TextColumn::make('industri.nama')->label('Tempat PKL')->searchable(),
                TextColumn::make('guru.nama')->label('Pembimbing')->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Selesai' => 'gray',
                        'Batal' => 'danger',
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
            'index' => Pages\ListPenempatanPkls::route('/'),
            'create' => Pages\CreatePenempatanPkl::route('/create'),
            'edit' => Pages\EditPenempatanPkl::route('/{record}/edit'),
        ];
    }
}
