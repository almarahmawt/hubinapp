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

class JurnalPklResource extends Resource
{
    protected static ?string $model = JurnalPkl::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Jurnal PKL';

    protected static ?string $pluralModelLabel = 'Jurnal PKL';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ----------------------------------------------------
                // 1. PILIHAN PENEMPATAN (Untuk Admin / Otomatis untuk Siswa)
                // ----------------------------------------------------
                Select::make('penempatan_pkl_id')
                    ->label('Siswa & Tempat PKL')
                    ->relationship('penempatanPkl', 'id')
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        $namaSiswa = $record->siswa->nama ?? 'Siswa Tidak Ditemukan';
                        $namaIndustri = $record->industri->nama_industri ?? 'Industri Tidak Ditemukan';
                        return "{$namaSiswa} - {$namaIndustri}";
                    })
                    ->searchable()
                    // Sembunyikan untuk Siswa (karena penempatan_pkl_id di-inject di CreateJurnalPkl.php)
                    ->hidden(fn () => Siswa::where('user_id', auth()->id())->exists()),

                // ----------------------------------------------------
                // 2. DATA KEHADIRAN & LOKASI
                // ----------------------------------------------------
                Section::make('Data Kehadiran & Lokasi')
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
                        ]),
                    ]),

                // ----------------------------------------------------
                // 3. REFLEKSI & JURNAL KEGIATAN
                // ----------------------------------------------------
                Section::make('Refleksi & Jurnal Kegiatan')
                    ->description('Ceritakan apa yang kamu kerjakan dan pelajari hari ini.')
                    // HANYA MUNCUL JIKA KETERANGANNYA "HADIR"
                    ->visible(fn (\Filament\Forms\Get $get) => $get('status_kehadiran') === 'Hadir')
                    ->schema([
                        TextInput::make('orang_disapa')
                            ->label('Siapa orang di tempat kerja yang kamu sapa hari ini?')
                            ->placeholder('Contoh: Pak Budi (Supervisor), Resepsionis, dll.'),

                        Grid::make(2)->schema([
                            Toggle::make('persiapan_alat')
                                ->label('Saya sudah menyiapkan alat sebelum bekerja')
                                ->onColor('success'),
                            Toggle::make('membereskan_alat')
                                ->label('Saya sudah membereskan alat setelah bekerja')
                                ->onColor('success'),
                        ]),

                        RichEditor::make('deskripsi_kegiatan')
                            ->label('Deskripsi Pekerjaan')
                            ->required(fn (\Filament\Forms\Get $get) => $get('status_kehadiran') === 'Hadir'),

                        FileUpload::make('foto_kegiatan')
                            ->label('Dokumentasi Visual (Foto/Screenshot)')
                            ->directory('foto-jurnal'),
                    ]),

                // ----------------------------------------------------
                // 4. AREA VALIDASI PEMBIMBING / ADMIN
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
            'index' => Pages\ListJurnalPkls::route('/'),
            'create' => Pages\CreateJurnalPkl::route('/create'),
            'edit' => Pages\EditJurnalPkl::route('/{record}/edit'),
        ];
    }
}