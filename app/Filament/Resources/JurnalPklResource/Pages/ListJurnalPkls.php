<?php

namespace App\Filament\Resources\JurnalPklResource\Pages;

use App\Filament\Resources\JurnalPklResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
                ->action(fn () => $this->exportJurnalPkl()),
            Actions\CreateAction::make(),
        ];
    }

    protected function exportJurnalPkl(): StreamedResponse
    {
        $records = $this->getFilteredSortedTableQuery()
            ->with('penempatanPkl.siswa.kelas', 'penempatanPkl.industri')
            ->get();

        $fileName = 'jurnal-pkl-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($records) {
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
                    optional($record->tanggal)->format('d-m-Y') ?? $record->tanggal,
                    $record->penempatanPkl?->siswa?->nama ?? '-',
                    $record->penempatanPkl?->siswa?->kelas?->nama ?? '-',
                    $record->penempatanPkl?->industri?->nama ?? '-',
                    $record->status_kehadiran,
                    $record->kedisiplinan ?? '-',
                    $record->sopan_santun_komunikasi ?? '-',
                    $record->tanggung_jawab_etos_kerja ?? '-',
                    $record->kepatuhan_keselamatan_kerja ?? '-',
                    filled($record->budaya_kerja_5r) ? implode(', ', $record->budaya_kerja_5r) : '-',
                    Str::of($record->deskripsi_kegiatan ?? '-')->stripTags()->squish(),
                    $record->status_validasi,
                    $record->catatan_pembimbing ?? '-',
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
