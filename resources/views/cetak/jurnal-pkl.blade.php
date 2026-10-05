<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <title>Jurnal PKL - {{ $penempatan->siswa?->nama }}</title>
        <style>
            @page {
                size: A4;
                margin: 15mm;
            }

            body {
                margin: 0;
                padding: 0;
                font-family: Helvetica, Arial, sans-serif;
                font-size: 12px;
                color: #111827;
                background: #fff;
            }

            .header {
                text-align: center;
                margin-bottom: 14px;
            }

            .header h1 {
                font-size: 15px;
                margin: 0;
                letter-spacing: 0.02em;
            }

            .header h2 {
                font-size: 13px;
                margin: 2px 0 0;
                font-weight: normal;
            }

            .info-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 14px;
            }

            .info-table td {
                padding: 1px 0;
                vertical-align: top;
                font-size: 12px;
            }

            .info-table .info-label {
                width: 150px;
                white-space: nowrap;
            }

            .info-table .info-sep {
                width: 12px;
            }

            .activity-table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }

            .activity-table th,
            .activity-table td {
                border: 1px solid #111827;
                padding: 6px 7px;
                font-size: 11.5px;
                vertical-align: top;
            }

            .activity-table th {
                background: #f3f4f6;
                text-align: center;
                font-weight: bold;
            }

            .col-hari {
                width: 15%;
                text-align: center;
            }

            .col-hari .hari-nama {
                font-weight: bold;
                text-transform: uppercase;
            }

            .col-hari .hari-tanggal {
                margin-top: 3px;
            }

            .col-paraf {
                width: 15%;
            }

            .kegiatan-text {
                white-space: pre-line;
            }

            .empty-row td {
                height: 50px;
            }

            .signature-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 36px;
            }

            .signature-table td {
                width: 50%;
                text-align: center;
                vertical-align: top;
                font-size: 12px;
            }

            .signature-space {
                height: 65px;
            }

            .signature-name {
                text-decoration: underline;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>JURNAL / KEGIATAN HARIAN SISWA</h1>
            <h1>PRAKTIK KERJA LAPANGAN</h1>
            <h2>TAHUN AJARAN {{ $tahunAjaran }}</h2>
        </div>

        <table class="info-table">
            <tr>
                <td class="info-label">Nama Siswa</td>
                <td class="info-sep">:</td>
                <td>{{ $penempatan->siswa?->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Kompetensi Keahlian</td>
                <td class="info-sep">:</td>
                <td>{{ $penempatan->siswa?->kompetensi?->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Nama Industri</td>
                <td class="info-sep">:</td>
                <td>{{ $penempatan->industri?->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Departemen/Divisi</td>
                <td class="info-sep">:</td>
                <td>{{ $departemenDivisi }}</td>
            </tr>
            <tr>
                <td class="info-label">Nama Instruktur</td>
                <td class="info-sep">:</td>
                <td>{{ $namaInstruktur ?: '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Jabatan</td>
                <td class="info-sep">:</td>
                <td>{{ $jabatanInstruktur ?: '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Minggu Ke</td>
                <td class="info-sep">:</td>
                <td>{{ $mingguKe }}</td>
            </tr>
        </table>

        <table class="activity-table">
            <thead>
                <tr>
                    <th class="col-hari">Hari/Tgl</th>
                    <th>Kegiatan / Aktivitas PKL</th>
                    <th class="col-paraf">Paraf Instruktur</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jurnals as $jurnal)
                    @php
                        $tanggal = \Illuminate\Support\Carbon::parse($jurnal->tanggal);
                        $namaHari = [
                            1 => 'SENIN', 2 => 'SELASA', 3 => 'RABU', 4 => 'KAMIS',
                            5 => 'JUMAT', 6 => 'SABTU', 7 => 'MINGGU',
                        ][$tanggal->isoWeekday()];
                    @endphp
                    <tr>
                        <td class="col-hari">
                            <div class="hari-nama">{{ $namaHari }}</div>
                            <div class="hari-tanggal">Tgl {{ $tanggal->format('d/m/y') }}</div>
                        </td>
                        <td>
                            @if ($jurnal->status_kehadiran !== 'Hadir')
                                <strong>{{ $jurnal->status_kehadiran }}</strong>
                            @else
                                <div class="kegiatan-text">{{ \Illuminate\Support\Str::of($jurnal->deskripsi_kegiatan ?? '-')->stripTags()->squish() }}</div>
                            @endif
                        </td>
                        <td class="col-paraf"></td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td class="col-hari"></td>
                        <td></td>
                        <td class="col-paraf"></td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <table class="signature-table">
            <tr>
                <td>Pembimbing Sekolah.</td>
                <td>Instruktur, {{ \Illuminate\Support\Carbon::parse($tanggalSelesai)->locale('id')->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="signature-space"></td>
                <td class="signature-space"></td>
            </tr>
            <tr>
                <td class="signature-name">{{ $penempatan->guru?->nama_lengkap ?? '-' }}</td>
                <td class="signature-name">{{ $namaInstruktur ?: '-' }}</td>
            </tr>
        </table>
    </body>
</html>
