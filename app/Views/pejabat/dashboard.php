<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- WELCOME BANNER PEJABAT PENILAI -->
<div class="bg-white/80 backdrop-blur-sm rounded-3xl p-6 md:p-7 shadow-sm border border-emerald-100 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-[#2D6A4F] text-white flex items-center justify-center text-2xl font-bold shadow-md shrink-0">
            ⚖️
        </div>
        <div>
            <h2 class="text-xl md:text-2xl font-extrabold text-gray-900 leading-tight">Selamat Datang, Pejabat Penilai!</h2>
            <p class="text-xs text-gray-600 mt-1">Anda memiliki hak untuk meninjau, menyetujui, atau menolak usulan kenaikan gaji dan pangkat pegawai DLH Bengkalis.</p>
        </div>
    </div>
    <a href="<?= base_url('pejabat/pengajuan') ?>" 
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#2D6A4F] hover:bg-[#255740] text-white text-xs font-bold transition shadow-sm transform active:scale-95 shrink-0">
        <span>Tinjau Pengajuan Masuk</span>
        <span>→</span>
    </a>
</div>

<!-- STATISTIC CARDS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <!-- Card 1: Menunggu Verifikasi -->
    <a href="<?= base_url('pejabat/pengajuan') ?>" 
       class="bg-[#2D6A4F] hover:bg-[#255740] text-white rounded-[22px] px-7 py-6 shadow-sm transition transform hover:-translate-y-0.5 flex items-center justify-center gap-6">
        <div class="shrink-0">
            <svg class="w-9 h-9 text-amber-300 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="text-center">
            <h3 class="text-sm font-semibold text-gray-100 tracking-wide">Menunggu Verifikasi</h3>
            <p class="text-xl font-bold text-white mt-0.5 tracking-wider font-mono"><?= esc($menungguCount ?? '00') ?></p>
        </div>
    </a>

    <!-- Card 2: Telah Disetujui -->
    <a href="<?= base_url('pejabat/laporan?status=Disetujui') ?>" 
       class="bg-[#2D6A4F] hover:bg-[#255740] text-white rounded-[22px] px-7 py-6 shadow-sm transition transform hover:-translate-y-0.5 flex items-center justify-center gap-6">
        <div class="shrink-0">
            <svg class="w-9 h-9 text-emerald-300 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="text-center">
            <h3 class="text-sm font-semibold text-gray-100 tracking-wide">Usulan Disetujui</h3>
            <p class="text-xl font-bold text-white mt-0.5 tracking-wider font-mono"><?= esc($disetujuiCount ?? '00') ?></p>
        </div>
    </a>

    <!-- Card 3: Usulan Ditolak -->
    <a href="<?= base_url('pejabat/laporan?status=Ditolak') ?>" 
       class="bg-[#2D6A4F] hover:bg-[#255740] text-white rounded-[22px] px-7 py-6 shadow-sm transition transform hover:-translate-y-0.5 flex items-center justify-center gap-6">
        <div class="shrink-0">
            <svg class="w-9 h-9 text-rose-300 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="text-center">
            <h3 class="text-sm font-semibold text-gray-100 tracking-wide">Usulan Ditolak</h3>
            <p class="text-xl font-bold text-white mt-0.5 tracking-wider font-mono"><?= esc($ditolakCount ?? '00') ?></p>
        </div>
    </a>

</div>

<!-- RECENT PENDING APPLICATIONS PREVIEW -->
<div class="bg-white/80 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-emerald-100">
    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
        <div>
            <h3 class="font-bold text-gray-800 text-base">Pengajuan Menunggu Tindakan</h3>
            <p class="text-xs text-gray-500">Daftar usulan kenaikan terbaru yang belum diputuskan</p>
        </div>
        <a href="<?= base_url('pejabat/pengajuan') ?>" class="text-xs font-bold text-[#2D6A4F] hover:underline">
            Lihat Semua →
        </a>
    </div>

    <?php if (! empty($pendingPengajuans)): ?>
        <div class="space-y-3">
            <?php foreach ($pendingPengajuans as $row): ?>
                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-emerald-50/60 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-xs">
                            ⏳
                        </div>
                        <div>
                            <p class="font-bold text-sm text-gray-900"><?= esc($row['nama'] ?? '-') ?></p>
                            <p class="text-xs text-gray-500 font-mono"><?= esc($row['nip'] ?? '-') ?> • <?= esc($row['jabatan'] ?? '-') ?></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-bold px-3 py-1 rounded-full <?= $row['jenis_kenaikan'] === 'Gaji' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' ?>">
                            Kenaikan <?= esc($row['jenis_kenaikan'] ?? '-') ?>
                        </span>
                        <a href="<?= base_url('pejabat/pengajuan') ?>" class="px-3 py-1.5 rounded-full bg-[#2D6A4F] text-white text-xs font-semibold hover:bg-[#255740] transition">
                            Proses
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="py-8 text-center text-gray-500">
            <p class="text-sm font-semibold">Semua pengajuan telah selesai diverifikasi!</p>
            <p class="text-xs text-gray-400 mt-0.5">Tidak ada antrean pengajuan yang menunggu saat ini.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
