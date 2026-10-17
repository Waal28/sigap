<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- TOP ACTION & SEARCH BAR -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 relative">
    <!-- Left: Arrow Icon + Title -->
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-800 shadow-sm shrink-0">
            <svg class="w-6 h-6 rotate-45 -mt-0.5 text-gray-800" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight leading-tight">Pengajuan Kenaikan</h2>
            <p class="text-xs text-gray-500 hidden sm:block">Verifikasi kelayakan berkas dan berikan persetujuan kenaikan pangkat dan gaji pegawai</p>
        </div>
    </div>

    <!-- Right Controls: Search & Filter -->
    <div class="flex items-center gap-3">
        <!-- Search Input Bar -->
        <form action="<?= base_url('pejabat/pengajuan') ?>" method="GET" class="relative m-0 p-0 flex items-center">
            <?php if (! empty($selectedJenis)): ?>
                <input type="hidden" name="jenis" value="<?= esc($selectedJenis) ?>">
            <?php endif; ?>
            <?php if (! empty($selectedStatus)): ?>
                <input type="hidden" name="status" value="<?= esc($selectedStatus) ?>">
            <?php endif; ?>
            <div class="relative">
                <input type="text" 
                       id="searchInputPejabat"
                       name="q"
                       value="<?= esc($keyword ?? '') ?>"
                       onkeyup="liveSearchPejabat()"
                       placeholder="Cari Nama....." 
                       class="w-48 sm:w-64 bg-white rounded-full pl-5 pr-9 py-2 text-sm text-gray-700 placeholder-gray-400 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#33634B] shadow-sm transition">
                <?php if (! empty($keyword)): ?>
                    <a href="<?= base_url('pejabat/pengajuan' . (! empty($selectedJenis) ? '?jenis=' . urlencode($selectedJenis) : '')) ?>" 
                       class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600" title="Hapus Pencarian">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <!-- Filter Funnel Button -->
        <div class="relative">
            <button type="button" 
                    id="filterBtnPejabat"
                    onclick="toggleFilterDropdownPejabat()"
                    class="w-10 h-10 rounded-full bg-[#33634B] hover:bg-[#264D39] text-white flex items-center justify-center shadow-md transition-all transform active:scale-95 shrink-0 <?= (! empty($selectedJenis) || ! empty($selectedStatus)) ? 'ring-2 ring-emerald-300 bg-[#264D39]' : '' ?>"
                    title="Filter Pengajuan">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </button>

            <!-- Filter Dropdown Popover -->
            <div id="filterDropdownPejabat" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 p-4 text-xs">
                <div class="flex items-center justify-between pb-2 mb-3 border-b border-gray-100">
                    <span class="font-bold text-gray-800 uppercase tracking-wider">Filter Penilaian</span>
                    <?php if (! empty($selectedJenis) || ! empty($selectedStatus)): ?>
                        <a href="<?= base_url('pejabat/pengajuan') ?>" class="text-rose-600 hover:underline">Reset</a>
                    <?php endif; ?>
                </div>

                <!-- Opsi Kenaikan -->
                <div class="mb-3">
                    <label class="block font-bold text-gray-600 mb-1">Jenis Kenaikan:</label>
                    <div class="flex gap-1.5">
                        <a href="<?= base_url('pejabat/pengajuan') ?>" 
                           class="px-2.5 py-1 rounded-lg border text-center flex-1 <?= empty($selectedJenis) ? 'bg-[#33634B] text-white font-bold' : 'border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
                            Semua
                        </a>
                        <a href="<?= base_url('pejabat/pengajuan?jenis=Gaji') ?>" 
                           class="px-2.5 py-1 rounded-lg border text-center flex-1 <?= $selectedJenis === 'Gaji' ? 'bg-[#33634B] text-white font-bold' : 'border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
                            Gaji
                        </a>
                        <a href="<?= base_url('pejabat/pengajuan?jenis=Pangkat') ?>" 
                           class="px-2.5 py-1 rounded-lg border text-center flex-1 <?= $selectedJenis === 'Pangkat' ? 'bg-[#33634B] text-white font-bold' : 'border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
                            Pangkat
                        </a>
                    </div>
                </div>

                <!-- Opsi Status -->
                <div>
                    <label class="block font-bold text-gray-600 mb-1">Status Usulan:</label>
                    <div class="space-y-1">
                        <a href="<?= base_url('pejabat/pengajuan?status=Menunggu') ?>" 
                           class="block px-3 py-1.5 rounded-lg <?= $selectedStatus === 'Menunggu' ? 'bg-amber-100 text-amber-900 font-bold' : 'text-gray-600 hover:bg-gray-50' ?>">
                            ⏳ Menunggu Penilaian
                        </a>
                        <a href="<?= base_url('pejabat/pengajuan?status=Disetujui') ?>" 
                           class="block px-3 py-1.5 rounded-lg <?= $selectedStatus === 'Disetujui' ? 'bg-emerald-100 text-emerald-900 font-bold' : 'text-gray-600 hover:bg-gray-50' ?>">
                            ✓ Sudah Disetujui
                        </a>
                        <a href="<?= base_url('pejabat/pengajuan?status=Ditolak') ?>" 
                           class="block px-3 py-1.5 rounded-lg <?= $selectedStatus === 'Ditolak' ? 'bg-rose-100 text-rose-900 font-bold' : 'text-gray-600 hover:bg-gray-50' ?>">
                            ✕ Ditolak
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DATA TABLE (EXACT PEJABAT PENILAI REVIEW DESIGN) -->
<div class="bg-white/80 backdrop-blur-sm rounded-[28px] overflow-hidden shadow-sm border border-emerald-100">
    <div class="overflow-x-auto">
        <table id="tablePengajuanPejabat" class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="border-b border-gray-200/80 text-gray-800 font-bold">
                    <th class="py-4 px-4 text-center w-12">No.</th>
                    <th class="py-4 px-4">Nama</th>
                    <th class="py-4 px-4">NIP</th>
                    <th class="py-4 px-4">Jabatan</th>
                    <th class="py-4 px-4">Masa Kerja</th>
                    <th class="py-4 px-4">Kenaikan</th>
                    <th class="py-4 px-4 text-center">Lampiran</th>
                    <th class="py-4 px-4 text-center w-36">Aksi</th>
                </tr>
            </thead>
            <tbody id="pejabatTableBody" class="divide-y divide-gray-100 text-gray-600 font-medium">
                <?php if (! empty($pengajuans)): ?>
                    <?php $no = 1; foreach ($pengajuans as $row): ?>
                        <tr class="pejabat-row hover:bg-emerald-50/60 transition duration-150"
                            data-nama="<?= esc($row['nama'] ?? '', 'attr') ?>"
                            data-nip="<?= esc($row['nip'] ?? '', 'attr') ?>"
                            data-jabatan="<?= esc($row['jabatan'] ?? '', 'attr') ?>"
                            data-kenaikan="<?= esc($row['jenis_kenaikan'] ?? '', 'attr') ?>"
                            data-status="<?= esc($row['status'] ?? '', 'attr') ?>">
                            <td class="py-3.5 px-4 text-center font-normal"><?= $no++ ?></td>
                            <td class="py-3.5 px-4 font-semibold text-gray-800"><?= esc($row['nama'] ?? '-') ?></td>
                            <td class="py-3.5 px-4 font-mono"><?= esc($row['nip'] ?? '-') ?></td>
                            <td class="py-3.5 px-4"><?= esc($row['jabatan'] ?? '-') ?></td>
                            <td class="py-3.5 px-4"><?= esc($row['masa_kerja'] ?? '-') ?></td>
                            <td class="py-3.5 px-4 font-semibold <?= $row['jenis_kenaikan'] === 'Gaji' ? 'text-emerald-700' : 'text-blue-700' ?>">
                                <?= esc($row['jenis_kenaikan'] ?? '-') ?>
                            </td>
                            <!-- Lampiran Badge PDF Hitam -->
                            <td class="py-3.5 px-4 text-center">
                                <button type="button" 
                                        onclick="openReviewModal(<?= $row['id'] ?>)"
                                        class="inline-flex items-center justify-center px-2.5 py-1 rounded bg-[#1A1A1A] hover:bg-black text-white text-[11px] font-extrabold tracking-wider transition shadow-sm transform hover:scale-105 active:scale-95"
                                        title="Buka Dokumen PDF">
                                    PDF
                                    <?php if ((int)($row['total_lampiran'] ?? 0) > 0): ?>
                                        <span class="ml-1 text-[9px] bg-white/20 px-1 rounded-full font-normal">
                                            <?= (int)$row['total_lampiran'] ?>
                                        </span>
                                    <?php endif; ?>
                                </button>
                            </td>
                            <!-- Aksi 3 Tombol (Mata Biru, Checklist Hijau, Silang Merah) -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-2.5">
                                    <!-- Detail (Mata Biru) -->
                                    <button type="button" 
                                            onclick="openReviewModal(<?= $row['id'] ?>)"
                                            class="w-7 h-7 rounded-full bg-sky-50 text-sky-600 hover:bg-sky-600 hover:text-white flex items-center justify-center transition shadow-sm transform hover:scale-110" 
                                            title="Tinjau Berkas & Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    <!-- Setujui (Checklist Hijau) -->
                                    <?php if ($row['status'] === 'Disetujui'): ?>
                                        <span class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-sm" title="Sudah Disetujui">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </span>
                                    <?php else: ?>
                                        <button type="button" 
                                                onclick="openApproveModal(<?= $row['id'] ?>, '<?= esc($row['nama'] ?? '', 'js') ?>', '<?= esc($row['jenis_kenaikan'] ?? '', 'js') ?>')"
                                                class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition shadow-sm transform hover:scale-110 active:scale-95" 
                                                title="Setujui Pengajuan">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    <?php endif; ?>

                                    <!-- Tolak (Silang Merah) -->
                                    <?php if ($row['status'] === 'Ditolak'): ?>
                                        <span class="w-7 h-7 rounded-full bg-rose-500 text-white flex items-center justify-center shadow-sm" title="Sudah Ditolak">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </span>
                                    <?php else: ?>
                                        <button type="button" 
                                                onclick="openRejectModal(<?= $row['id'] ?>, '<?= esc($row['nama'] ?? '', 'js') ?>', '<?= esc($row['jenis_kenaikan'] ?? '', 'js') ?>')"
                                                class="w-7 h-7 rounded-full bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white flex items-center justify-center transition shadow-sm transform hover:scale-110 active:scale-95" 
                                                title="Tolak Pengajuan">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="font-semibold text-gray-600">Tidak ada pengajuan kenaikan yang menunggu penilaian</p>
                                <p class="text-xs text-gray-400 mt-1">Seluruh usulan kenaikan gaji dan pangkat yang diajukan pegawai akan tampil di sini.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                <tr id="noMatchRowPejabat" class="hidden">
                    <td colspan="8" class="py-8 text-center text-gray-500 font-medium">
                        Tidak ada pengajuan yang cocok dengan pencarian.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL REVIEW DETAIL & BERKAS               -->
<!-- ========================================== -->
<div id="modalReview" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all">
        <!-- Header Modal -->
        <div class="bg-[#33634B] px-6 py-4 flex items-center justify-between text-white">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-sm">Peninjauan Pengajuan Kenaikan</h3>
            </div>
            <button type="button" onclick="closeReviewModal()" class="text-white/80 hover:text-white text-xl font-bold leading-none focus:outline-none">
                &times;
            </button>
        </div>

        <div class="p-6">
            <!-- Employee Info Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-gray-100 gap-3">
                <div>
                    <h4 id="rev_nama" class="font-extrabold text-base text-gray-900 leading-tight">-</h4>
                    <p class="text-xs text-gray-500 mt-0.5">
                        NIP: <span id="rev_nip" class="font-mono font-bold text-gray-700">-</span> | Jabatan: <span id="rev_jabatan" class="font-semibold text-gray-700">-</span>
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span id="rev_jenis_badge" class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                        -
                    </span>
                    <span id="rev_status_badge" class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                        Menunggu
                    </span>
                </div>
            </div>

            <!-- Meta Details -->
            <div class="grid grid-cols-2 gap-4 py-3 text-xs bg-gray-50/70 p-3 rounded-2xl my-3">
                <div>
                    <span class="text-gray-500 block">Masa Kerja:</span>
                    <span id="rev_masa_kerja" class="font-bold text-gray-800">-</span>
                </div>
                <div>
                    <span class="text-gray-500 block">Tanggal Diajukan:</span>
                    <span id="rev_created_at" class="font-bold text-gray-800">-</span>
                </div>
            </div>

            <!-- Documents List -->
            <div class="mt-4">
                <h5 class="font-bold text-xs text-gray-700 uppercase tracking-wider mb-2">Berkas Dokumen Lampiran:</h5>
                <div id="rev_lampiran_list" class="space-y-2 max-h-56 overflow-y-auto">
                    <div class="py-4 text-center text-gray-400 text-xs">Memuat berkas...</div>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                <button type="button" 
                        onclick="closeReviewModal()" 
                        class="px-6 py-2 rounded-full border border-gray-300 text-gray-700 text-xs font-bold hover:bg-gray-100 transition w-full sm:w-auto">
                    Tutup
                </button>
                <div id="rev_action_buttons" class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <!-- Dynamic Approve & Reject buttons populated via JS -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL KONFIRMASI SETUJUI                   -->
<!-- ========================================== -->
<div id="modalApprove" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl overflow-hidden p-6 text-center transform transition-all">
        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h3 class="text-base font-bold text-gray-900 mb-2">Setujui Pengajuan Kenaikan?</h3>
        <p class="text-xs text-gray-500 mb-5">
            Apakah Anda yakin ingin menyetujui usulan <span id="approve_jenis" class="font-bold"></span> untuk pegawai <span id="approve_nama" class="font-bold text-gray-800"></span>?
        </p>

        <form id="formApprove" action="" method="POST" class="space-y-3">
            <?= csrf_field() ?>
            <input type="text" name="catatan" placeholder="Catatan persetujuan (opsional)" 
                   class="w-full px-4 py-2 text-xs rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <div class="flex items-center justify-center gap-3 pt-2">
                <button type="button" 
                        onclick="closeApproveModal()" 
                        class="px-5 py-2.5 rounded-full border border-gray-300 text-gray-700 text-xs font-semibold hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md transition transform active:scale-95">
                    Ya, Setujui
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL KONFIRMASI TOLAK                    -->
<!-- ========================================== -->
<div id="modalReject" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl overflow-hidden p-6 text-center transform transition-all">
        <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-600 mx-auto flex items-center justify-center mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <h3 class="text-base font-bold text-gray-900 mb-2">Tolak Pengajuan Kenaikan?</h3>
        <p class="text-xs text-gray-500 mb-4">
            Berikan alasan atau catatan penolakan untuk pengajuan milik <span id="reject_nama" class="font-bold text-gray-800"></span>:
        </p>

        <form id="formReject" action="" method="POST" class="space-y-3">
            <?= csrf_field() ?>
            <textarea name="catatan" 
                      required 
                      rows="3" 
                      placeholder="Masukkan alasan penolakan berkas (wajib diisi)..." 
                      class="w-full px-4 py-2 text-xs rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-rose-500 text-left"></textarea>
            <div class="flex items-center justify-center gap-3 pt-2">
                <button type="button" 
                        onclick="closeRejectModal()" 
                        class="px-5 py-2.5 rounded-full border border-gray-300 text-gray-700 text-xs font-semibold hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-full bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md transition transform active:scale-95">
                    Tolak Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Live Search Pejabat
    function liveSearchPejabat() {
        const query = document.getElementById('searchInputPejabat').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.pejabat-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const nama = row.getAttribute('data-nama').toLowerCase();
            const nip = row.getAttribute('data-nip').toLowerCase();
            const jabatan = row.getAttribute('data-jabatan').toLowerCase();
            const kenaikan = row.getAttribute('data-kenaikan').toLowerCase();

            if (nama.includes(query) || nip.includes(query) || jabatan.includes(query) || kenaikan.includes(query)) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        const noMatch = document.getElementById('noMatchRowPejabat');
        if (noMatch) {
            if (visibleCount === 0 && rows.length > 0) {
                noMatch.classList.remove('hidden');
            } else {
                noMatch.classList.add('hidden');
            }
        }
    }

    // Toggle Filter Pejabat
    function toggleFilterDropdownPejabat() {
        document.getElementById('filterDropdownPejabat').classList.toggle('hidden');
    }
    document.addEventListener('click', function(e) {
        const btn = document.getElementById('filterBtnPejabat');
        const drop = document.getElementById('filterDropdownPejabat');
        if (btn && drop && !btn.contains(e.target) && !drop.contains(e.target)) {
            drop.classList.add('hidden');
        }
    });

    // Review Modal
    function openReviewModal(id) {
        const list = document.getElementById('rev_lampiran_list');
        list.innerHTML = '<div class="py-4 text-center text-gray-400 text-xs">Memuat berkas...</div>';
        document.getElementById('modalReview').classList.remove('hidden');

        fetch("<?= base_url('pejabat/pengajuan/detail') ?>/" + id)
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success' && res.data) {
                    const d = res.data;
                    document.getElementById('rev_nama').innerText = d.nama;
                    document.getElementById('rev_nip').innerText = d.nip;
                    document.getElementById('rev_jabatan').innerText = d.jabatan;
                    document.getElementById('rev_masa_kerja').innerText = d.masa_kerja;
                    document.getElementById('rev_created_at').innerText = d.created_at;

                    const jenisBadge = document.getElementById('rev_jenis_badge');
                    jenisBadge.innerText = 'Kenaikan ' + d.jenis_kenaikan;

                    const statusBadge = document.getElementById('rev_status_badge');
                    statusBadge.innerText = d.status;
                    statusBadge.className = 'px-3 py-1 rounded-full text-xs font-bold ' + 
                        (d.status === 'Disetujui' ? 'bg-emerald-100 text-emerald-800' : 
                        (d.status === 'Ditolak' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800'));

                    // Lampiran List
                    if (d.lampiran && d.lampiran.length > 0) {
                        let html = '';
                        d.lampiran.forEach(l => {
                            html += `
                                <div class="flex items-center justify-between p-2.5 rounded-xl bg-gray-50 border border-gray-100 text-xs">
                                    <div class="flex items-center gap-2 truncate mr-2">
                                        <span class="w-6 h-6 rounded bg-rose-100 text-rose-700 font-bold text-[9px] flex items-center justify-center shrink-0">PDF</span>
                                        <span class="font-semibold text-gray-800 truncate">${l.nama_dokumen}</span>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <a href="${l.view_url}" target="_blank" class="px-2.5 py-1 rounded-full bg-emerald-100 text-[#33634B] font-bold text-[10px] hover:bg-emerald-200 transition">
                                            Buka
                                        </a>
                                        <a href="${l.download_url}" class="px-2.5 py-1 rounded-full bg-gray-200 text-gray-700 font-bold text-[10px] hover:bg-gray-300 transition">
                                            Unduh
                                        </a>
                                    </div>
                                </div>
                            `;
                        });
                        list.innerHTML = html;
                    } else {
                        list.innerHTML = '<div class="py-4 text-center text-gray-400 text-xs">Tidak ada berkas yang diunggah.</div>';
                    }

                    // Action buttons in modal
                    const actionContainer = document.getElementById('rev_action_buttons');
                    if (d.status === 'Menunggu') {
                        actionContainer.innerHTML = `
                            <button type="button" onclick="closeReviewModal(); openRejectModal(${d.id}, '${d.nama}', '${d.jenis_kenaikan}')" 
                                    class="px-4 py-2 rounded-full bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white font-bold text-xs transition">
                                Tolak Usulan
                            </button>
                            <button type="button" onclick="closeReviewModal(); openApproveModal(${d.id}, '${d.nama}', '${d.jenis_kenaikan}')" 
                                    class="px-5 py-2 rounded-full bg-[#33634B] hover:bg-[#264D39] text-white font-bold text-xs shadow transition">
                                Setujui Usulan
                            </button>
                        `;
                    } else {
                        actionContainer.innerHTML = `<span class="text-xs text-gray-400 italic">Status: ${d.status}</span>`;
                    }
                }
            })
            .catch(() => {
                list.innerHTML = '<div class="py-4 text-center text-rose-500 text-xs">Gagal mengambil rincian pengajuan.</div>';
            });
    }
    function closeReviewModal() {
        document.getElementById('modalReview').classList.add('hidden');
    }

    // Modal Approve
    function openApproveModal(id, nama, jenis) {
        document.getElementById('approve_nama').innerText = nama;
        document.getElementById('approve_jenis').innerText = jenis;
        document.getElementById('formApprove').action = "<?= base_url('pejabat/pengajuan/approve') ?>/" + id;
        document.getElementById('modalApprove').classList.remove('hidden');
    }
    function closeApproveModal() {
        document.getElementById('modalApprove').classList.add('hidden');
    }

    // Modal Reject
    function openRejectModal(id, nama, jenis) {
        document.getElementById('reject_nama').innerText = nama;
        document.getElementById('formReject').action = "<?= base_url('pejabat/pengajuan/reject') ?>/" + id;
        document.getElementById('modalReject').classList.remove('hidden');
    }
    function closeRejectModal() {
        document.getElementById('modalReject').classList.add('hidden');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeReviewModal();
            closeApproveModal();
            closeRejectModal();
        }
    });
</script>

<?= $this->endSection() ?>
