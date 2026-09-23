<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Hubungan Industri (HUBIN) & SIM PKL</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <!-- Navbar Hubin -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Logo HUBIN -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-md">
                    H
                </div>
                <div>
                    <span class="text-xl font-extrabold text-slate-900 tracking-tight">PORTAL <span class="text-blue-600">HUBIN</span></span>
                    <span class="block text-xs font-semibold text-slate-400 -mt-1">Hubungan Industri & BKK</span>
                </div>
            </div>

            <!-- Navigasi Utama -->
            <nav class="hidden md:flex items-center gap-8 font-medium text-slate-600 text-sm">
                <a href="#tentang" class="hover:text-blue-600 transition-colors">Profil Hubin</a>
                <a href="#layanan" class="hover:text-blue-600 transition-colors">Layanan & Program</a>
                <a href="#sim-pkl" class="hover:text-blue-600 font-semibold text-blue-600 transition-colors">SIM PKL</a>
                <a href="#mitra" class="hover:text-blue-600 transition-colors">Mitra Industri</a>
            </nav>

            <!-- CTA Akses Sistem -->
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/admin') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 shadow-md transition-all">
                        Dashboard Utama
                    </a>
                @else
                    <a href="{{ url('/login') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-slate-900 text-white font-semibold text-sm hover:bg-slate-800 transition-all">
                        Masuk Sistem
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section: Fokus Utama HUBIN -->
    <section class="relative pt-12 pb-20 md:pt-20 md:pb-24 bg-gradient-to-b from-white to-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-xs font-bold uppercase tracking-wider">
                    🏛️ Pusat Karir & Kemitraan Industri
                </div>

                <h1 class="text-4xl md:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    Membangun Sinergi Antara <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Pendidikan & Dunia Kerja</span>
                </h1>

                <p class="text-base md:text-lg text-slate-600 leading-relaxed">
                    Selamat datang di Portal Resmi Hubungan Industri (HUBIN). Kami menjembatani peserta didik dengan dunia usaha/dunia industri (DUDI) melalui program PKL, rekrutmen tenaga kerja (BKK), dan kerjasama strategis.
                </p>

                <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="#sim-pkl" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-blue-600 text-white font-semibold shadow-xl shadow-blue-500/25 hover:bg-blue-700 transition-all">
                        Akses SIM PKL Digital →
                    </a>
                    <a href="#layanan" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-white text-slate-700 font-semibold border border-slate-200 hover:bg-slate-50 transition-all">
                        Eksplor Layanan Hubin
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SPECIAL SECTION: SIM PKL (Highlight Utama) -->
    <section id="sim-pkl" class="py-16 bg-blue-600 text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-blue-700/50 backdrop-blur-sm border border-blue-400/30 rounded-3xl p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="space-y-4 max-w-2xl">
                    <div class="inline-block px-3 py-1 bg-white/10 rounded-lg text-xs font-bold uppercase tracking-wider text-blue-100">
                        Sistem Informasi Manajemen
                    </div>
                    <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                        SIM PKL (Praktek Kerja Lapangan)
                    </h2>
                    <p class="text-blue-100 text-base leading-relaxed">
                        Layanan digitalisasi pelaksanaan PKL secara menyeluruh. Mulai dari pendaftaran mandiri, pencatatan jurnal & presensi harian siswa, hingga validasi nilai oleh Guru Pembimbing & Pembimbing Industri.
                    </p>
                    <div class="pt-2 flex flex-wrap gap-4 text-sm font-medium text-blue-100">
                        <span class="flex items-center gap-1.5">✓ Jurnal Harian Realtime</span>
                        <span class="flex items-center gap-1.5">✓ Ploting Industri</span>
                        <span class="flex items-center gap-1.5">✓ Penilaian Digital</span>
                    </div>
                </div>

                <div class="w-full md:w-auto text-center md:text-right">
                    <a href="{{ url('/login') }}" class="inline-block w-full md:w-auto px-8 py-4 rounded-2xl bg-white text-blue-600 font-extrabold text-base shadow-2xl hover:bg-blue-50 transition-all">
                        Masuk ke SIM PKL
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan-Layanan HUBIN Lainnya -->
    <section id="layanan" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Program & Layanan HUBIN</h2>
                <p class="text-slate-600 mt-3">Ruang lingkup kerja Hubungan Industri dalam mendukung kesiapan kerja lulusan.</p>
            </div>

            <div class="grid md:grid-cols-4 gap-6">
                <!-- Layanan 1: SIM PKL -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 font-bold text-xl flex items-center justify-center mb-5">
                        📍
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2">SIM PKL</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Pengelolaan Praktek Kerja Lapangan siswa terintegrasi secara online.</p>
                </div>

                <!-- Layanan 2: BKK / Bursa Kerja -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 font-bold text-xl flex items-center justify-center mb-5">
                        💼
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2">Bursa Kerja Khusus (BKK)</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Informasi lowongan kerja, rekrutmen kampus, dan penelusuran alumni (Tracer Study).</p>
                </div>

                <!-- Layanan 3: Kunjungan Industri -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 font-bold text-xl flex items-center justify-center mb-5">
                        🚌
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2">Kunjungan & Guru Tamu</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Program kunjungan industri siswa serta pendatangan praktisi ahli ke sekolah.</p>
                </div>

                <!-- Layanan 4: Kelas Industri / Kerjasama -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 font-bold text-xl flex items-center justify-center mb-5">
                        🤝
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2">Kerjasama DUDI</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Penandatanganan MoU, sinkronisasi kurikulum, dan pembukaan Kelas Industri.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-slate-500">
                &copy; {{ date('Y') }} Tim Hubungan Industri & Masyarakat (HUBIN). All rights reserved.
            </p>
            <div class="flex items-center gap-6 text-sm text-slate-500">
                <a href="#sim-pkl" class="hover:text-blue-600 font-semibold">SIM PKL</a>
                <a href="{{ url('/login') }}" class="hover:text-blue-600">Login Sistem</a>
            </div>
        </div>
    </footer>

</body>
</html>