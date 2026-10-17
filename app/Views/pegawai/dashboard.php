<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- WELCOME BANNER PEGAWAI -->
<div class="bg-white/80 backdrop-blur-sm rounded-3xl p-6 md:p-7 shadow-sm border border-emerald-100 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-[#2D6A4F] text-white flex items-center justify-center text-2xl font-bold shadow-md shrink-0">
            👋
        </div>
        <div>
            <h2 class="text-xl md:text-2xl font-extrabold text-gray-900 leading-tight">
                Selamat Datang, <?= esc($pegawai->nama ?? session()->get('nama')) ?>!
            </h2>
            <p class="text-xs text-gray-600 mt-1 font-mono">
                NIP: <?= esc($pegawai->nip ?? session()->get('nip')) ?> • Jabatan: <?= esc($pegawai->jabatan ?? '-') ?> • Masa Kerja: <?= esc($pegawai->masa_kerja ?? '-') ?>
            </p>
        </div>
    </div>
    <a href="<?= base_url('pegawai/pengajuan') ?>" 
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#2D6A4F] hover:bg-[#255740] text-white text-xs font-bold transition shadow-sm transform active:scale-95 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span>Ajukan Kenaikan Baru</span>
    </a>
</div>

<!-- STATISTIC CARDS -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">

    <!-- Card 1: Total Pengajuan -->
    <a href="<?= base_url('pegawai/laporan') ?>" 
       class="bg-[#2D6A4F] hover:bg-[#255740] text-white rounded-[22px] px-5 py-5 shadow-sm transition transform hover:-translate-y-0.5 flex items-center justify-between">
        <div>
            <h3 class="text-xs font-semibold text-gray-100">Total Pengajuan</h3>
            <p class="text-xl font-bold text-white mt-1 font-mono"><?= esc($totalPengajuan ?? '00') ?></p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white rotate-45 -mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
            </svg>
        </div>
    </a>

    <!-- Card 2: Menunggu -->
    <a href="<?= base_url('pegawai/laporan?status=Menunggu') ?>" 
       class="bg-[#2D6A4F] hover:bg-[#255740] text-white rounded-[22px] px-5 py-5 shadow-sm transition transform hover:-translate-y-0.5 flex items-center justify-between">
        <div>
            <h3 class="text-xs font-semibold text-gray-100">Menunggu Review</h3>
            <p class="text-xl font-bold text-amber-300 mt-1 font-mono"><?= esc($menungguCount ?? '00') ?></p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
            <span class="text-base">⏳</span>
        </div>
    </a>

    <!-- Card 3: Disetujui -->
    <a href="<?= base_url('pegawai/laporan?status=Disetujui') ?>" 
       class="bg-[#2D6A4F] hover:bg-[#255740] text-white rounded-[22px] px-5 py-5 shadow-sm transition transform hover:-translate-y-0.5 flex items-center justify-between">
        <div>
            <h3 class="text-xs font-semibold text-gray-100">Disetujui</h3>
            <p class="text-xl font-bold text-emerald-300 mt-1 font-mono"><?= esc($disetujuiCount ?? '00') ?></p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
            <span class="text-base">✓</span>
        </div>
    </a>

    <!-- Card 4: Ditolak -->
    <a href="<?= base_url('pegawai/laporan?status=Ditolak') ?>" 
       class="bg-[#2D6A4F] hover:bg-[#255740] text-white rounded-[22px] px-5 py-5 shadow-sm transition transform hover:-translate-y-0.5 flex items-center justify-between">
        <div>
            <h3 class="text-xs font-semibold text-gray-100">Ditolak / Catatan</h3>
            <p class="text-xl font-bold text-rose-300 mt-1 font-mono"><?= esc($ditolakCount ?? '00') ?></p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
            <span class="text-base">✕</span>
        </div>
    </a>

</div>

<!-- RECENT PERSONAL SUBMISSIONS -->
<div class="bg-white/80 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-emerald-100">
    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
        <div>
            <h3 class="font-bold text-gray-800 text-base">Status Pengajuan Terbaru Anda</h3>
            <p class="text-xs text-gray-500">Pantau proses kenaikan pangkat dan berkala secara transparan</p>
        </div>
        <a href="<?= base_url('pegawai/laporan') ?>" class="text-xs font-bold text-[#2D6A4F] hover:underline">
            Lihat Riwayat Lengkap →
        </a>
    </div>

    <?php if (! empty($recentPengajuans)): ?>
        <div class="space-y-3">
            <?php foreach ($recentPengajuans as $row): ?>
                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-emerald-50/60 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold <?= $row['status'] === 'Disetujui' ? 'bg-emerald-100 text-emerald-800' : ($row['status'] === 'Ditolak' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') ?>">
                            <?= $row['status'] === 'Disetujui' ? '✓' : ($row['status'] === 'Ditolak' ? '✕' : '⏳') ?>
                        </div>
                        <div>
                            <p class="font-bold text-sm text-gray-900">Kenaikan <?= esc($row['jenis_kenaikan'] ?? '-') ?></p>
                            <p class="text-xs text-gray-500">Diajukan pada: <?= date('d M Y, H:i', strtotime($row['created_at'])) ?> WIB</p>
                            <?php if (! empty($row['catatan'])): ?>
                                <p class="text-[11px] text-gray-600 mt-0.5 italic">Catatan: "<?= esc($row['catatan']) ?>"</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold px-3 py-1 rounded-full <?= $row['status'] === 'Disetujui' ? 'bg-emerald-100 text-emerald-800' : ($row['status'] === 'Ditolak' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') ?>">
                            <?= esc($row['status']) ?>
                        </span>
                        <a href="<?= base_url('pegawai/laporan') ?>" class="px-3 py-1.5 rounded-full bg-gray-200 text-gray-700 text-xs font-semibold hover:bg-gray-300 transition">
                            Detail
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="py-10 text-center text-gray-500">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-sm font-semibold text-gray-700">Belum ada pengajuan kenaikan yang dibuat.</p>
            <p class="text-xs text-gray-400 mt-0.5">Silakan klik tombol di bawah untuk membuat pengajuan baru.</p>
            <a href="<?= base_url('pegawai/pengajuan') ?>" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-[#2D6A4F] hover:bg-[#255740] text-white text-xs font-bold rounded-full shadow transition">
                <span>Ajukan Kenaikan Sekarang</span>
                <span>→</span>
            </a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
