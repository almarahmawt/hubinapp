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
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\PendaftaranPkl;
use App\Models\Siswa;

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
                TextColumn::make('pendaftaran_count')
                    ->counts('pendaftaran')
                    ->label('Jumlah Pendaftar')
                    ->badge()
                    ->color(fn ($state, $record) => $state > $record->kuota ? 'danger' : 'success'),
            ])
            ->filters([])
            ->actions([
                // Tombol Lamar (Hanya muncul untuk role Siswa)
                Action::make('lamar')
                    ->label('Lamar Sekarang')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pendaftaran')
                    ->modalDescription('Apakah kamu yakin ingin melamar di lowongan ini?')
                    ->modalSubmitActionLabel('Ya, Lamar!')
                    ->visible(fn () => auth()->user()->hasRole(['Siswa', 'super_admin'])) // Hanya Siswa yang bisa melihat ini
                    ->action(function ($record) {
                        // Cari profil siswa berdasarkan user yang sedang login
                        $siswa = Siswa::where('user_id', auth()->id())->first();

                        if (!$siswa) {
                            Notification::make()
                                ->title('Gagal: Profil Siswa tidak ditemukan.')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Cek apakah siswa sudah melamar di tempat yang sama
                        $sudahDaftar = PendaftaranPkl::where('siswa_id', $siswa->id)
                            ->where('lowongan_pkl_id', $record->id)
                            ->exists();

                        if ($sudahDaftar) {
                            Notification::make()
                                ->title('Kamu sudah melamar di lowongan ini sebelumnya!')
                                ->warning()
                                ->send();
                            return;
                        }

                        // Buat data pendaftaran baru
                        PendaftaranPkl::create([
                            'siswa_id' => $siswa->id,
                            'lowongan_pkl_id' => $record->id,
                            'status' => 'Menunggu',
                        ]);

                        Notification::make()
                            ->title('Berhasil melamar! Silakan tunggu persetujuan.')
                            ->success()
                            ->send();
                    }),

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
