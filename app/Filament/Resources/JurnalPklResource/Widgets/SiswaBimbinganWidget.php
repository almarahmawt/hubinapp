<?php

namespace App\Filament\Resources\JurnalPklResource\Widgets;

use App\Models\PenempatanPkl;
use App\Models\Guru;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class SiswaBimbinganWidget extends BaseWidget
{
    protected static ?string $heading = 'Daftar Siswa Bimbingan PKL';

    // Membuat widget tampil dengan lebar penuh di atas tabel
    protected int|string|array $columnSpan = 'full';

    // Widget hanya tampil jika user bertipe Guru, Staf PKL, Admin, atau Super Admin
    public static function canView(): bool
    {
        $user = auth()->user();
        return $user?->hasRole(['Guru', 'Staf PKL', 'Admin', 'super_admin']) ??
            false;
    }

    public function table(Table $table): Table
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user?->id)->first();

        return $table
            ->query(
                PenempatanPkl::query()
                    // Jika login sebagai Guru, otomatis difilter hanya siswa bimbingannya
                    ->when($user?->hasRole('Guru'), function ($query) use (
                        $guru,
                    ) {
                        $query->where('guru_id', $guru?->id ?? 0);
                    })
                    ->with(['siswa.kelas', 'industri', 'guru']),
            )
            ->columns([
                Tables\Columns\TextColumn::make('siswa.nis')
                    ->label('NIS')
                    ->searchable(),

                Tables\Columns\TextColumn::make('siswa.nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('siswa.kelas.nama')
                    ->label('Kelas')
                    ->sortable(),

                Tables\Columns\TextColumn::make('industri.nama')
                    ->label('Tempat PKL (Industri)')
                    ->searchable(),

                Tables\Columns\TextColumn::make('guru.nama_lengkap')
                    ->label('Guru Pembimbing')
                    ->visible(
                        fn() => auth()
                            ->user()
                            ?->hasRole(['Staf PKL', 'Admin', 'super_admin']),
                    ),
            ])
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5);
    }
}
