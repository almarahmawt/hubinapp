<?php

namespace App\Filament\Resources;


use App\Models\Siswa;
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
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

use Illuminate\Database\Eloquent\Model;



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
                    ->default(fn () => auth()->user()->hasRole('Siswa') ? auth()->user()->siswa?->id : null)
                    ->disabled(fn () => auth()->user()->hasRole('Siswa')) // Gembok jika dia punya role Siswa
                    ->dehydrated()
                    ->required(),
                    
                Select::make('lowongan_pkl_id')
                    ->relationship('lowonganPkl', 'id')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->industri->nama} - Kuota: {$record->kuota}")
                    ->label('Pilih Tempat PKL')
                    ->searchable()
                    ->preload()
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
                    ->disabled(fn () => auth()->user()->hasRole('Siswa')) // Gembok jika dia punya role Siswa
                    ->dehydrated()
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
               // Tombol Setujui (Hanya untuk Admin)
                Tables\Actions\Action::make('setujui') // <-- Tambahkan Tables\
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Pendaftaran')
                    ->visible(fn () => auth()->user()->hasRole(['Admin','super_admin']))
                    ->action(function (PendaftaranPkl $record) {
                        $record->update(['status' => 'Disetujui']);
                        
                        \Filament\Notifications\Notification::make() // <-- Tambahkan \Filament\Notifications\
                            ->title('Pendaftaran Disetujui')
                            ->success()
                            ->send();
                    }),

                // Tombol Tolak (Hanya untuk Admin)
                Tables\Actions\Action::make('tolak') // <-- Tambahkan Tables\
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn () => auth()->user()->hasRole(['Admin','super_admin']))
                    ->action(function (PendaftaranPkl $record) {
                        $record->update(['status' => 'Ditolak']);
                        
                        \Filament\Notifications\Notification::make() // <-- Tambahkan \Filament\Notifications\
                            ->title('Pendaftaran Ditolak')
                            ->danger()
                            ->send();
                    }),
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('Siswa')) {
            // Jika akun belum disambungkan ke biodata, gunakan ID -1 (mustahil ada) agar tabel kosong
            $siswaId = auth()->user()->siswa?->id ?? -1; 
            $query->where('siswa_id', $siswaId);
        }

        return $query;
    }
}
