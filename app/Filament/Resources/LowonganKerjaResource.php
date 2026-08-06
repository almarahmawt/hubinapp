<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LowonganKerjaResource\Pages;
use App\Models\LowonganKerja;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// Tambahkan komponen-komponen ini
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class LowonganKerjaResource extends Resource
{
    protected static ?string $model = LowonganKerja::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase'; // Ganti icon jadi koper kerja
    protected static ?string $navigationLabel = 'BKK / Lowongan Kerja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // INI ADALAH FITUR RELASINYA
                Select::make('industri_id')
                    ->relationship('industri', 'nama_perusahaan') // Mengambil fungsi industri() di Model dan menampilkan nama_perusahaan
                    ->required()
                    ->searchable() // Agar admin bisa mengetik nama perusahaan jika daftarnya panjang
                    ->preload()
                    ->label('Perusahaan / Industri Mitra'),

                TextInput::make('posisi_pekerjaan')
                    ->required()
                    ->maxLength(255)
                    ->label('Posisi Pekerjaan (Misal: Staff IT, Analis Kimia)'),

                DatePicker::make('batas_lamaran')
                    ->label('Batas Akhir Lamaran'),

                Toggle::make('status_aktif')
                    ->default(true)
                    ->label('Status Loker Aktif?'),

                Textarea::make('deskripsi')
                    ->columnSpanFull()
                    ->label('Deskripsi & Syarat Pekerjaan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menampilkan nama perusahaan dari tabel relasi
                TextColumn::make('industri.nama_perusahaan')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('posisi_pekerjaan')
                    ->label('Posisi')
                    ->searchable(),

                TextColumn::make('batas_lamaran')
                    ->label('Batas Lamaran')
                    ->date() // Format tampilan tanggal
                    ->sortable(),

                // Toggle column agar admin bisa mematikan/menyalakan loker langsung dari tabel
                ToggleColumn::make('status_aktif')
                    ->label('Aktif'),
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
            'index' => Pages\ListLowonganKerjas::route('/'),
            'create' => Pages\CreateLowonganKerja::route('/create'),
            'edit' => Pages\EditLowonganKerja::route('/{record}/edit'),
        ];
    }
}