<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- STATISTIC CARDS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Card 1: Jumlah Pegawai -->
    <a href="<?= base_url('pegawai') ?>" 
       class="bg-[#2D6A4F] hover:bg-[#255740] text-white rounded-[22px] px-7 py-6 shadow-sm transition transform hover:-translate-y-0.5 flex items-center justify-center gap-6">
        <div class="shrink-0">
            <svg class="w-9 h-9 text-white drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="text-center">
            <h3 class="text-sm font-semibold text-gray-100 tracking-wide">Jumlah Pegawai</h3>
            <p class="text-xl font-bold text-white mt-0.5 tracking-wider font-mono"><?= esc($pegawaiCount ?? '00') ?></p>
        </div>
    </a>

    <!-- Card 2: Jumlah Pengajuan -->
    <a href="<?= base_url('pengajuan') ?>" 
       class="bg-[#2D6A4F] hover:bg-[#255740] text-white rounded-[22px] px-7 py-6 shadow-sm transition transform hover:-translate-y-0.5 flex items-center justify-center gap-6">
        <div class="shrink-0">
            <svg class="w-9 h-9 text-white rotate-45 -mt-0.5 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
            </svg>
        </div>
        <div class="text-center">
            <h3 class="text-sm font-semibold text-gray-100 tracking-wide">Jumlah Pengajuan</h3>
            <p class="text-xl font-bold text-white mt-0.5 tracking-wider font-mono"><?= esc($pengajuanCount ?? '00') ?></p>
        </div>
    </a>

    <!-- Card 3: Jumlah Laporan -->
    <a href="<?= base_url('laporan') ?>" 
       class="bg-[#2D6A4F] hover:bg-[#255740] text-white rounded-[22px] px-7 py-6 shadow-sm transition transform hover:-translate-y-0.5 flex items-center justify-center gap-6">
        <div class="shrink-0">
            <svg class="w-9 h-9 text-white drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="text-center">
            <h3 class="text-sm font-semibold text-gray-100 tracking-wide">Jumlah Laporan</h3>
            <p class="text-xl font-bold text-white mt-0.5 tracking-wider font-mono"><?= esc($laporanCount ?? '00') ?></p>
        </div>
    </a>

</div>

<?= $this->endSection() ?>
