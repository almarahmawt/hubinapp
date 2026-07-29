<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndustriResource\Pages;
use App\Filament\Resources\IndustriResource\RelationManagers;
use App\Models\Industri;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// INI YANG BARU KITA TAMBAHKAN AGAR BISA MEMBUAT KOTAK INPUT DAN TABEL
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class IndustriResource extends Resource
{
    protected static ?string $model = Industri::class;

    // Kamu bisa ganti iconnya nanti
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2'; 
    protected static ?string $navigationLabel = 'Data Industri';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_perusahaan')
                    ->label('Nama Perusahaan / Industri')
                    ->required() // Wajib diisi!
                    ->maxLength(255),
                
                TextInput::make('kontak_hrd')
                    ->label('Kontak HRD')
                    ->maxLength(255),
                
                TextInput::make('kuota')
                    ->label('Kuota PKL')
                    ->numeric()
                    ->default(0),
                
                Textarea::make('alamat')
                    ->label('Alamat Lengkap')
                    ->columnSpanFull(), // Agar isian memanjang
                
                Textarea::make('syarat_khusus')
                    ->label('Syarat Khusus (Opsional)')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_perusahaan')
                    ->label('Nama Perusahaan')
                    ->searchable() // Agar bisa dicari di kolom search
                    ->sortable(),
                
                TextColumn::make('kuota')
                    ->label('Kuota')
                    ->sortable()
                    ->badge(), // Menampilkan kuota dengan bentuk kotak cantik
                
                TextColumn::make('kontak_hrd')
                    ->label('Kontak HRD')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // Tambahan tombol hapus
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
            'index' => Pages\ListIndustris::route('/'),
            'create' => Pages\CreateIndustri::route('/create'),
            'edit' => Pages\EditIndustri::route('/{record}/edit'),
        ];
    }
}