<?php

namespace App\Filament\Resources\JurnalPklResource\Pages;

use App\Filament\Resources\JurnalPklResource;
use App\Models\Guru;
use App\Models\PenempatanPkl;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ListJurnalPkls extends ListRecords
{
    protected static string $resource = JurnalPklResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn() => $this->exportJurnalPkl()),
            Actions\Action::make('cetak')
                ->label('Cetak Jurnal')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->form($this->getCetakFormSchema())
                ->action(fn (array $data) => $this->cetakJurnalPdf($data)),
            Actions\CreateAction::make(),
        ];
    }

    /**
     * @return array<\Filament\Forms\Components\Component>
     */
    protected function getCetakFormSchema(): array
    {
        $user = auth()->user();
        $siswaSendiri = Siswa::where('user_id', $user->id)->first();
        $guruSendiri = Guru::where('user_id', $user->id)->first();

        return [
            Select::make('penempatan_pkl_id')
                ->label('Siswa & Tempat PKL')
                ->options(function () use ($user, $guruSendiri) {
                    $query = PenempatanPkl::with(['siswa', 'industri']);

                    if ($user->hasRole('Guru')) {
                        $query->where('guru_id', $guruSendiri?->id ?? 0);
                    }

                    return $query->get()->mapWithKeys(fn ($record) => [
                        $record->id => ($record->siswa->nama ?? 'Siswa Tidak Ditemukan')
                            . ' - ' . ($record->industri->nama ?? 'Industri Tidak Ditemukan'),
                    ]);
                })
                ->searchable()
                ->preload()
                ->required()
                ->default(fn () => $siswaSendiri
                    ? PenempatanPkl::where('siswa_id', $siswaSendiri->id)->latest()->value('id')
                    : null)
                ->hidden(fn () => $siswaSendiri !== null),

            DatePicker::make('tanggal_mulai')
                ->label('Tanggal Mulai')
                ->required(),

            DatePicker::make('tanggal_selesai')
                ->label('Tanggal Selesai')
                ->required(),

            TextInput::make('minggu_ke')
                ->label('Minggu Ke')
                ->numeric(),

            TextInput::make('tahun_ajaran')
                ->label('Tahun Ajaran')
                ->default(fn () => now()->month >= 7
                    ? now()->year . '/' . (now()->year + 1)
                    : (now()->year - 1) . '/' . now()->year),

            TextInput::make('departemen_divisi')
                ->label('Departemen / Divisi'),

            TextInput::make('nama_instruktur')
                ->label('Nama Instruktur (Industri)'),

            TextInput::make('jabatan_instruktur')
                ->label('Jabatan Instruktur'),
        ];
    }

    protected function cetakJurnalPdf(array $data): StreamedResponse
    {
        // Field penempatan_pkl_id disembunyikan untuk Siswa (lihat getCetakFormSchema()),
        // jadi tidak ikut terkirim di $data. Isi manual dari data siswa yang login,
        // sama seperti pola di CreateJurnalPkl::mutateFormDataBeforeCreate().
        if (empty($data['penempatan_pkl_id'])) {
            $siswaSendiri = Siswa::where('user_id', auth()->id())->first();

            $data['penempatan_pkl_id'] = $siswaSendiri
                ? PenempatanPkl::where('siswa_id', $siswaSendiri->id)->latest()->value('id')
                : null;
        }

        if (empty($data['penempatan_pkl_id'])) {
            throw new AccessDeniedHttpException('Anda belum terdaftar di tempat PKL manapun.');
        }

        $penempatan = PenempatanPkl::with(['siswa.kompetensi', 'industri', 'guru'])
            ->findOrFail($data['penempatan_pkl_id']);

        $this->authorizeCetak($penempatan);

        $jurnals = $penempatan->jurnalPkls()
            ->whereBetween('tanggal', [$data['tanggal_mulai'], $data['tanggal_selesai']])
            ->orderBy('tanggal')
            ->get();

        $pdf = Pdf::loadView('cetak.jurnal-pkl', [
            'penempatan' => $penempatan,
            'jurnals' => $jurnals,
            'tanggalMulai' => $data['tanggal_mulai'],
            'tanggalSelesai' => $data['tanggal_selesai'],
            'mingguKe' => $data['minggu_ke'] ?? '-',
            'tahunAjaran' => $data['tahun_ajaran'] ?? '-',
            'departemenDivisi' => $data['departemen_divisi'] ?? '-',
            'namaInstruktur' => $data['nama_instruktur'] ?? '',
            'jabatanInstruktur' => $data['jabatan_instruktur'] ?? '',
        ])->setPaper('a4', 'portrait');

        $fileName = 'jurnal-pkl-' . Str::slug($penempatan->siswa?->nama ?? 'siswa') . '-' . now()->format('Ymd-His') . '.pdf';
        $pdfOutput = $pdf->output();

        // Livewire cuma mengenali StreamedResponse/BinaryFileResponse sebagai file
        // download (lihat Livewire\Features\SupportFileDownloads). Pdf::download()
        // mengembalikan Illuminate\Http\Response biasa yang tidak terdeteksi dan
        // bikin Livewire gagal saat coba meng-encode isi PDF sebagai JSON.
        return response()->streamDownload(function () use ($pdfOutput) {
            echo $pdfOutput;
        }, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    protected function authorizeCetak(PenempatanPkl $penempatan): void
    {
        $user = auth()->user();

        if ($user->hasRole(['Staf PKL', 'super_admin'])) {
            return;
        }

        if ($user->hasRole('Guru') && Guru::where('user_id', $user->id)->value('id') === $penempatan->guru_id) {
            return;
        }

        if ($user->hasRole('Siswa') && Siswa::where('user_id', $user->id)->value('id') === $penempatan->siswa_id) {
            return;
        }

        throw new AccessDeniedHttpException('Anda tidak memiliki akses ke jurnal ini.');
    }

    protected function exportJurnalPkl(): StreamedResponse
    {
        $records = $this->getFilteredSortedTableQuery()
            ->with('penempatanPkl.siswa.kelas', 'penempatanPkl.industri')
            ->get();

        $fileName = 'jurnal-pkl-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(
            function () use ($records) {
                $handle = fopen('php://output', 'w');

                // BOM supaya Excel membaca file sebagai UTF-8 (karakter "–" dkk tidak jadi mojibake)
                fwrite($handle, "\xEF\xBB\xBF");

                fputcsv($handle, [
                    'Tanggal',
                    'Nama Siswa',
                    'Kelas',
                    'Industri',
                    'Status Kehadiran',
                    'Kedisiplinan',
                    'Sopan Santun & Komunikasi',
                    'Tanggung Jawab & Etos Kerja',
                    'Kepatuhan & Keselamatan Kerja',
                    'Budaya Kerja 5R',
                    'Deskripsi Kegiatan',
                    'Status Validasi',
                    'Catatan Pembimbing',
                ]);

                foreach ($records as $record) {
                    fputcsv($handle, [
                        optional($record->tanggal)->format('d-m-Y') ??
                        $record->tanggal,
                        $record->penempatanPkl?->siswa?->nama ?? '-',
                        $record->penempatanPkl?->siswa?->kelas?->nama ?? '-',
                        $record->penempatanPkl?->industri?->nama ?? '-',
                        $record->status_kehadiran,
                        $record->kedisiplinan ?? '-',
                        $record->sopan_santun_komunikasi ?? '-',
                        $record->tanggung_jawab_etos_kerja ?? '-',
                        $record->kepatuhan_keselamatan_kerja ?? '-',
                        filled($record->budaya_kerja_5r)
                            ? implode(', ', $record->budaya_kerja_5r)
                            : '-',
                        \App\Models\JurnalPkl::plainTextKegiatan($record->deskripsi_kegiatan),
                        $record->status_validasi,
                        $record->catatan_pembimbing ?? '-',
                    ]);
                }

                fclose($handle);
            },
            $fileName,
            [
                'Content-Type' => 'text/csv',
            ],
        );
    }
}
