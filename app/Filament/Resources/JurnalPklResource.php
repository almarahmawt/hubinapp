<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurnalPklResource\Pages;
use App\Models\JurnalPkl;
use App\Models\Siswa;
use App\Models\PenempatanPkl;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

class JurnalPklResource extends Resource
{
    protected static ?string $model = JurnalPkl::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Jurnal PKL';

    protected static ?string $pluralModelLabel = 'Jurnal PKL';

    /**
     * Guru tidak boleh mengedit jurnal sama sekali. Staf PKL & super_admin selalu
     * boleh mengedit walaupun jurnalnya sudah divalidasi. Role lain (mis. Siswa)
     * hanya boleh mengedit selama status validasinya belum "Disetujui".
     */
    public static function canEditJurnal(JurnalPkl $record): bool
    {
        $user = auth()->user();

        if ($user?->hasRole('Guru')) {
            return false;
        }

        if ($user?->hasRole(['Staf PKL', 'super_admin'])) {
            return true;
        }

        return $record->status_validasi !== 'Disetujui';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ----------------------------------------------------
                // 1. PILIHAN PENEMPATAN (Untuk Admin / Otomatis untuk Siswa)
                // ----------------------------------------------------
                Select::make('penempatan_pkl_id')
                    ->label('Siswa & Tempat PKL')
                    ->options(fn () => PenempatanPkl::with(['siswa', 'industri'])
                        ->get()
                        ->mapWithKeys(fn ($record) => [
                            $record->id => ($record->siswa->nama ?? 'Siswa Tidak Ditemukan')
                                . ' - ' . ($record->industri->nama ?? 'Industri Tidak Ditemukan'),
                        ]))
                    ->searchable()
                    ->preload()
                    // Sembunyikan untuk Siswa (karena penempatan_pkl_id di-inject di CreateJurnalPkl.php)
                    ->hidden(fn () => Siswa::where('user_id', auth()->id())->exists()),

                // ----------------------------------------------------
                // CATATAN REVISI DARI PEMBIMBING (Untuk Siswa)
                // ----------------------------------------------------
                Section::make('Catatan Revisi dari Pembimbing')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->iconColor('danger')
                    ->extraAttributes([
                        'style' => 'background-color: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.4);',
                    ])
                    ->visible(fn (?JurnalPkl $record) => $record
                        && $record->status_validasi === 'Revisi'
                        && filled($record->catatan_pembimbing))
                    ->schema([
                        Placeholder::make('catatan_pembimbing_display')
                            ->hiddenLabel()
                            ->content(fn (?JurnalPkl $record) => $record?->catatan_pembimbing),
                    ]),

                // ----------------------------------------------------
                // 2. DATA KEHADIRAN & LOKASI
                // ----------------------------------------------------
                Section::make('Data Kehadiran')
                    ->description('Pilih status kehadiranmu hari ini.')
                    ->schema([
                        Grid::make(2)->schema([
                            DatePicker::make('tanggal')
                                ->default(now())
                                ->required(),

                            Select::make('status_kehadiran')
                                ->options([
                                    'Hadir' => 'Hadir',
                                    'Sakit' => 'Sakit',
                                    'Izin' => 'Izin',
                                ])
                                ->default('Hadir')
                                ->live() // Mendeteksi perubahan secara real-time
                                ->required(),
                        ]),

                        FileUpload::make('bukti_kehadiran')
                            ->label('Unggah Bukti (Surat Dokter / Surat Izin)')
                            ->directory('bukti-absensi')
                            ->visible(fn (\Filament\Forms\Get $get) => in_array($get('status_kehadiran'), ['Sakit', 'Izin'])),

                        Grid::make(2)->schema([
                            TextInput::make('latitude')->numeric()->readOnly(),
                            TextInput::make('longitude')->numeric()->readOnly(),
                        ])->hidden(),
                    ]),

                // ----------------------------------------------------
                // 3. PEMBIASAAN & BUDAYA KERJA HARIAN PKL
                // ----------------------------------------------------
                Section::make('Pembiasaan & Budaya Kerja Harian PKL')
                    ->description('Jawab pertanyaan berikut sebelum mengisi aktivitas hari ini.')
                    ->visible(fn (\Filament\Forms\Get $get) => $get('status_kehadiran') === 'Hadir')
                    ->schema([
                        Radio::make('kedisiplinan')
                            ->label('1. Kedisiplinan. Apakah saya hadir tepat waktu, siap bekerja, dan mengikuti ketentuan jam kerja hari ini?')
                            ->options([
                                'Sudah saya lakukan dengan baik' => 'Sudah saya lakukan dengan baik',
                                'Sudah saya lakukan, tetapi masih perlu diperbaiki' => 'Sudah saya lakukan, tetapi masih perlu diperbaiki',
                                'Belum saya lakukan' => 'Belum saya lakukan',
                            ])
                            ->required(fn (\Filament\Forms\Get $get) => $get('status_kehadiran') === 'Hadir'),

                        Radio::make('sopan_santun_komunikasi')
                            ->label('2. Sopan Santun & Komunikasi. Apakah saya berkomunikasi dengan sopan, jelas, dan santun, mendengarkan ketika orang lain berbicara, serta menyampaikan pertanyaan atau informasi dengan cara yang baik?')
                            ->options([
                                'Sudah saya lakukan dengan baik' => 'Sudah saya lakukan dengan baik',
                                'Sudah saya lakukan, tetapi masih perlu diperbaiki' => 'Sudah saya lakukan, tetapi masih perlu diperbaiki',
                                'Belum saya lakukan' => 'Belum saya lakukan',
                            ])
                            ->required(fn (\Filament\Forms\Get $get) => $get('status_kehadiran') === 'Hadir'),

                        Radio::make('tanggung_jawab_etos_kerja')
                            ->label('3. Tanggung Jawab & Etos Kerja. Apakah saya melaksanakan tugas dengan sungguh-sungguh, bertanggung jawab, berinisiatif, dan mau menerima masukan?')
                            ->options([
                                'Sudah saya lakukan dengan baik' => 'Sudah saya lakukan dengan baik',
                                'Sudah saya lakukan, tetapi masih perlu diperbaiki' => 'Sudah saya lakukan, tetapi masih perlu diperbaiki',
                                'Belum saya lakukan' => 'Belum saya lakukan',
                            ])
                            ->required(fn (\Filament\Forms\Get $get) => $get('status_kehadiran') === 'Hadir'),

                        Radio::make('kepatuhan_keselamatan_kerja')
                            ->label('4. Kepatuhan & Keselamatan Kerja. Apakah saya mematuhi tata tertib, SOP, ketentuan keselamatan kerja, serta menjaga fasilitas dan peralatan yang digunakan?')
                            ->options([
                                'Sudah saya lakukan dengan baik' => 'Sudah saya lakukan dengan baik',
                                'Sudah saya lakukan, tetapi masih perlu diperbaiki' => 'Sudah saya lakukan, tetapi masih perlu diperbaiki',
                                'Belum saya lakukan' => 'Belum saya lakukan',
                            ])
                            ->required(fn (\Filament\Forms\Get $get) => $get('status_kehadiran') === 'Hadir'),

                        CheckboxList::make('budaya_kerja_5r')
                            ->label('5. Budaya Kerja. Budaya kerja 5R apa yang sudah saya terapkan hari ini?')
                            ->options([
                                'Ringkas – memilah barang yang diperlukan dan tidak diperlukan' => 'Ringkas – memilah barang yang diperlukan dan tidak diperlukan',
                                'Rapi – menata barang/peralatan pada tempatnya' => 'Rapi – menata barang/peralatan pada tempatnya',
                                'Resik – menjaga kebersihan tempat dan peralatan kerja' => 'Resik – menjaga kebersihan tempat dan peralatan kerja',
                                'Rawat – menjaga kondisi dan keteraturan lingkungan kerja' => 'Rawat – menjaga kondisi dan keteraturan lingkungan kerja',
                                'Rajin – membiasakan 5R secara konsisten dan disiplin' => 'Rajin – membiasakan 5R secara konsisten dan disiplin',
                                'Belum menerapkan 5R hari ini' => 'Belum menerapkan 5R hari ini',
                            ])
                            ->columns(1),
                    ]),

                // ----------------------------------------------------
                // 4. REFLEKSI & JURNAL KEGIATAN
                // ----------------------------------------------------
                Section::make('Jurnal Kegiatan')
                    ->description('Ceritakan apa yang kamu kerjakan dan pelajari hari ini.')
                    // HANYA MUNCUL JIKA KETERANGANNYA "HADIR"
                    ->visible(fn (\Filament\Forms\Get $get) => $get('status_kehadiran') === 'Hadir')
                    ->schema([
                        TextInput::make('orang_disapa')
                            ->label('Siapa orang di tempat kerja yang kamu sapa hari ini?')
                            ->placeholder('Contoh: Pak Budi (Supervisor), Resepsionis, dll.')
                            ->hidden(),

                        Grid::make(2)->schema([
                            Toggle::make('persiapan_alat')
                                ->label('Saya sudah menyiapkan alat sebelum bekerja')
                                ->onColor('success'),
                            Toggle::make('membereskan_alat')
                                ->label('Saya sudah membereskan alat setelah bekerja')
                                ->onColor('success'),
                        ])->hidden(),

                        RichEditor::make('deskripsi_kegiatan')
                            ->label('Deskripsi Pekerjaan')
                            ->required(fn (\Filament\Forms\Get $get) => $get('status_kehadiran') === 'Hadir'),

                        FileUpload::make('foto_kegiatan')
                            ->label('Dokumentasi Visual (Foto/Screenshot)')
                            ->directory('foto-jurnal')
                            ->hidden(),
                    ]),

                // ----------------------------------------------------
                // 5. AREA VALIDASI PEMBIMBING / ADMIN
                // ----------------------------------------------------
                Section::make('Area Validasi Pembimbing')
                    // HANYA DITAMPILKAN UNTUK ADMIN ATAU SUPER ADMIN
                    ->visible(fn () => auth()->user()->hasAnyRole(['Admin', 'super_admin']))
                    ->schema([
                        Select::make('status_validasi')
                            ->options([
                                'Menunggu' => 'Menunggu',
                                'Disetujui' => 'Disetujui',
                                'Revisi' => 'Revisi',
                            ])
                            ->default('Menunggu')
                            ->required(),
                        Textarea::make('catatan_pembimbing')
                            ->label('Catatan / Feedback untuk Siswa'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('penempatanPkl.siswa.nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('penempatanPkl.siswa.kelas.nama')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status_kehadiran')
                    ->label('Kehadiran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Sakit' => 'warning',
                        'Izin' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('deskripsi_kegiatan')
                    ->label('Kegiatan')
                    ->formatStateUsing(fn (?string $state): string => \Illuminate\Support\Str::of($state ?? '')->stripTags()->squish())
                    ->limit(40)
                    ->default('-'),

                Tables\Columns\TextColumn::make('status_validasi')
                    ->label('Validasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Disetujui' => 'success',
                        'Revisi' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('tanggal', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status_kehadiran')
                    ->options([
                        'Hadir' => 'Hadir',
                        'Sakit' => 'Sakit',
                        'Izin' => 'Izin',
                    ]),
                Tables\Filters\SelectFilter::make('status_validasi')
                    ->options([
                        'Menunggu' => 'Menunggu',
                        'Disetujui' => 'Disetujui',
                        'Revisi' => 'Revisi',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('setujui_validasi')
                    ->label('Validasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Validasi Jurnal PKL')
                    ->modalDescription('Setujui jurnal ini?')
                    ->visible(fn (JurnalPkl $record) => auth()->user()->hasRole(['Guru', 'Staf PKL', 'Admin', 'super_admin'])
                        && $record->status_validasi !== 'Disetujui')
                    ->action(function (JurnalPkl $record) {
                        $record->update(['status_validasi' => 'Disetujui']);

                        Notification::make()
                            ->title('Jurnal berhasil divalidasi')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('revisi_validasi')
                    ->label('Revisi')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->form([
                        Textarea::make('catatan_pembimbing')
                            ->label('Catatan Pembimbing')
                            ->required()
                            ->default(fn (JurnalPkl $record) => $record->catatan_pembimbing),
                    ])
                    ->visible(fn (JurnalPkl $record) => auth()->user()->hasRole(['Guru', 'Staf PKL', 'Admin', 'super_admin'])
                        && $record->status_validasi !== 'Disetujui')
                    ->action(function (array $data, JurnalPkl $record) {
                        $record->update([
                            'status_validasi' => 'Revisi',
                            'catatan_pembimbing' => $data['catatan_pembimbing'],
                        ]);

                        Notification::make()
                            ->title('Jurnal dikembalikan untuk revisi')
                            ->warning()
                            ->send();
                    }),
                Tables\Actions\EditAction::make()
                    ->visible(fn (JurnalPkl $record) => static::canEditJurnal($record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('validasi')
                        ->label('Validasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Validasi Jurnal PKL Terpilih')
                        ->visible(fn () => auth()->user()->hasRole(['Guru', 'Staf PKL', 'Admin', 'super_admin']))
                        ->action(function (Collection $records) {
                            $records->each(fn (JurnalPkl $record) => $record->update(['status_validasi' => 'Disetujui']));

                            Notification::make()
                                ->title('Jurnal terpilih berhasil divalidasi')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (Collection $records) {
                            $protected = $records->where('status_validasi', 'Disetujui');
                            $records->reject(fn (JurnalPkl $record) => $record->status_validasi === 'Disetujui')
                                ->each(fn (JurnalPkl $record) => $record->delete());

                            if ($protected->isNotEmpty()) {
                                Notification::make()
                                    ->title($protected->count() . ' jurnal yang sudah divalidasi tidak dihapus')
                                    ->warning()
                                    ->send();
                            }
                        }),
                ]),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        if ($user?->hasRole(['Staf PKL', 'super_admin'])) {
            return $query;
        }

        if ($user?->hasRole('Guru')) {
            $guru = \App\Models\Guru::where('user_id', $user->id)->first();

            return $query->whereHas('penempatanPkl', function ($subQuery) use ($guru) {
                $subQuery->where('guru_id', $guru?->id ?? 0);
            });
        }

        if ($user?->hasRole('Siswa')) {
            $siswa = Siswa::where('user_id', $user->id)->first();

            return $query->whereHas('penempatanPkl', function ($subQuery) use ($siswa) {
                $subQuery->where('siswa_id', $siswa?->id ?? 0);
            });
        }

        return $query;
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
            'index' => Pages\ListJurnalPkls::route('/'),
            'create' => Pages\CreateJurnalPkl::route('/create'),
            'edit' => Pages\EditJurnalPkl::route('/{record}/edit'),
        ];
    }
}