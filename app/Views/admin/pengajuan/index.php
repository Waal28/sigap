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
            <p class="text-xs text-gray-500 hidden sm:block">Kelola dan pantau seluruh data usulan kenaikan gaji dan pangkat pegawai</p>
        </div>
    </div>

    <!-- Right Controls: Search & Filter -->
    <div class="flex items-center gap-3">
        <!-- Search Input Bar -->
        <form action="<?= base_url('admin/pengajuan') ?>" method="GET" class="relative m-0 p-0 flex items-center">
            <?php if (! empty($selectedJenis)): ?>
                <input type="hidden" name="jenis" value="<?= esc($selectedJenis) ?>">
            <?php endif; ?>
            <?php if (! empty($selectedStatus)): ?>
                <input type="hidden" name="status" value="<?= esc($selectedStatus) ?>">
            <?php endif; ?>
            <div class="relative">
                <input type="text" 
                       id="searchInput"
                       name="q"
                       value="<?= esc($keyword ?? '') ?>"
                       onkeyup="liveSearchPengajuan()"
                       placeholder="Cari Nama....." 
                       class="w-48 sm:w-64 bg-white rounded-full pl-5 pr-9 py-2 text-sm text-gray-700 placeholder-gray-400 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#33634B] shadow-sm transition">
                <?php if (! empty($keyword)): ?>
                    <a href="<?= base_url('admin/pengajuan' . (! empty($selectedJenis) ? '?jenis=' . urlencode($selectedJenis) : '')) ?>" 
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
                    id="filterBtn"
                    onclick="toggleFilterDropdown()"
                    class="w-10 h-10 rounded-full bg-[#33634B] hover:bg-[#264D39] text-white flex items-center justify-center shadow-md transition-all transform active:scale-95 shrink-0 <?= (! empty($selectedJenis) || ! empty($selectedStatus)) ? 'ring-2 ring-emerald-300 bg-[#264D39]' : '' ?>"
                    title="Filter Pengajuan">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </button>

            <!-- Filter Dropdown Popover -->
            <div id="filterDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 p-4 text-xs">
                <div class="flex items-center justify-between pb-2 mb-3 border-b border-gray-100">
                    <span class="font-bold text-gray-800 uppercase tracking-wider">Filter Pengajuan</span>
                    <?php if (! empty($selectedJenis) || ! empty($selectedStatus)): ?>
                        <a href="<?= base_url('admin/pengajuan') ?>" class="text-rose-600 hover:underline">Reset</a>
                    <?php endif; ?>
                </div>

                <!-- Opsi Kenaikan -->
                <div class="mb-3">
                    <label class="block font-bold text-gray-600 mb-1">Jenis Kenaikan:</label>
                    <div class="flex gap-1.5">
                        <a href="<?= base_url('admin/pengajuan') ?>" 
                           class="px-2.5 py-1 rounded-lg border text-center flex-1 <?= empty($selectedJenis) ? 'bg-[#33634B] text-white font-bold' : 'border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
                            Semua
                        </a>
                        <a href="<?= base_url('admin/pengajuan?jenis=Gaji') ?>" 
                           class="px-2.5 py-1 rounded-lg border text-center flex-1 <?= $selectedJenis === 'Gaji' ? 'bg-[#33634B] text-white font-bold' : 'border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
                            Gaji
                        </a>
                        <a href="<?= base_url('admin/pengajuan?jenis=Pangkat') ?>" 
                           class="px-2.5 py-1 rounded-lg border text-center flex-1 <?= $selectedJenis === 'Pangkat' ? 'bg-[#33634B] text-white font-bold' : 'border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
                            Pangkat
                        </a>
                    </div>
                </div>

                <!-- Opsi Status -->
                <div>
                    <label class="block font-bold text-gray-600 mb-1">Status Usulan:</label>
                    <div class="space-y-1">
                        <a href="<?= base_url('admin/pengajuan?status=Menunggu') ?>" 
                           class="block px-3 py-1.5 rounded-lg <?= $selectedStatus === 'Menunggu' ? 'bg-amber-100 text-amber-900 font-bold' : 'text-gray-600 hover:bg-gray-50' ?>">
                            ⏳ Menunggu
                        </a>
                        <a href="<?= base_url('admin/pengajuan?status=Disetujui') ?>" 
                           class="block px-3 py-1.5 rounded-lg <?= $selectedStatus === 'Disetujui' ? 'bg-emerald-100 text-emerald-900 font-bold' : 'text-gray-600 hover:bg-gray-50' ?>">
                            ✓ Disetujui
                        </a>
                        <a href="<?= base_url('admin/pengajuan?status=Ditolak') ?>" 
                           class="block px-3 py-1.5 rounded-lg <?= $selectedStatus === 'Ditolak' ? 'bg-rose-100 text-rose-900 font-bold' : 'text-gray-600 hover:bg-gray-50' ?>">
                            ✕ Ditolak
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DATA TABLE (EXACT ADMIN LIST DESIGN) -->
<div class="bg-white/80 backdrop-blur-sm rounded-[28px] overflow-hidden shadow-sm border border-emerald-100">
    <div class="overflow-x-auto">
        <table id="tablePengajuan" class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="border-b border-gray-200/80 text-gray-800 font-bold">
                    <th class="py-4 px-4 text-center w-12">No.</th>
                    <th class="py-4 px-4">Nama</th>
                    <th class="py-4 px-4">NIP</th>
                    <th class="py-4 px-4">Jabatan</th>
                    <th class="py-4 px-4">Masa Kerja</th>
                    <th class="py-4 px-4">Kenaikan</th>
                    <th class="py-4 px-4 text-center">Lampiran</th>
                    <th class="py-4 px-4 text-center w-28">Aksi</th>
                </tr>
            </thead>
            <tbody id="pengajuanTableBody" class="divide-y divide-gray-100 text-gray-600 font-medium">
                <?php if (! empty($pengajuans)): ?>
                    <?php $no = 1; foreach ($pengajuans as $row): ?>
                        <tr class="pengajuan-row hover:bg-emerald-50/60 transition duration-150"
                            data-nama="<?= esc($row['nama'] ?? '', 'attr') ?>"
                            data-nip="<?= esc($row['nip'] ?? '', 'attr') ?>"
                            data-jabatan="<?= esc($row['jabatan'] ?? '', 'attr') ?>"
                            data-kenaikan="<?= esc($row['jenis_kenaikan'] ?? '', 'attr') ?>">
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
                                        onclick="openLampiranModal(<?= $row['id'] ?>, '<?= esc($row['nama'] ?? '', 'js') ?>')"
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
                            <!-- Aksi Hapus (Icon Sampah Merah) -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center">
                                    <button type="button" 
                                            onclick="openHapusPengajuanModal(<?= $row['id'] ?>, '<?= esc($row['nama'] ?? '', 'js') ?>')"
                                            class="text-[#C0392B] hover:text-red-700 transition transform hover:scale-110" 
                                            title="Hapus Pengajuan">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
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
                                <p class="font-semibold text-gray-600">Belum ada data pengajuan kenaikan</p>
                                <p class="text-xs text-gray-400 mt-1">Pengajuan yang diajukan oleh pegawai akan tampil pada daftar ini.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                <tr id="noMatchRow" class="hidden">
                    <td colspan="8" class="py-8 text-center text-gray-500 font-medium">
                        Tidak ada data pengajuan yang cocok dengan pencarian.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DAFTAR LAMPIRAN PDF                 -->
<!-- ========================================== -->
<div id="modalLampiran" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all">
        <!-- Header Modal -->
        <div class="bg-[#1A1A1A] px-6 py-4 flex items-center justify-between text-white">
            <div class="flex items-center gap-2.5">
                <span class="px-2 py-0.5 rounded bg-white text-black font-extrabold text-xs">PDF</span>
                <h3 class="font-bold text-sm">Berkas Dokumen Lampiran</h3>
            </div>
            <button type="button" onclick="closeLampiranModal()" class="text-white/80 hover:text-white text-xl font-bold leading-none focus:outline-none">
                &times;
            </button>
        </div>

        <div class="p-6">
            <p class="text-xs text-gray-500 mb-3">
                Dokumen pengajuan kenaikan milik <span id="lampiran_owner" class="font-bold text-gray-800"></span>:
            </p>
            <div id="lampiranList" class="space-y-2 max-h-72 overflow-y-auto">
                <div class="py-6 text-center text-gray-400 text-xs">Memuat dokumen...</div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" 
                        onclick="closeLampiranModal()" 
                        class="px-6 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL HAPUS PENGAJUAN                     -->
<!-- ========================================== -->
<div id="modalHapusPengajuan" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl overflow-hidden p-6 text-center transform transition-all">
        <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-600 mx-auto flex items-center justify-center mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <h3 class="text-base font-bold text-gray-900 mb-2">Hapus Pengajuan Kenaikan?</h3>
        <p class="text-xs text-gray-500 mb-6">
            Apakah Anda yakin ingin menghapus data pengajuan milik <span id="hapus_pengajuan_nama" class="font-bold text-gray-800"></span>? Seluruh berkas lampiran terkait juga akan dihapus.
        </p>

        <form id="formHapusPengajuan" action="" method="POST" class="flex items-center justify-center gap-3">
            <?= csrf_field() ?>
            <button type="button" 
                    onclick="closeHapusPengajuanModal()" 
                    class="px-5 py-2.5 rounded-full border border-gray-300 text-gray-700 text-xs font-semibold hover:bg-gray-100 transition">
                Batal
            </button>
            <button type="submit" 
                    class="px-5 py-2.5 rounded-full bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md transition transform active:scale-95">
                Ya, Hapus
            </button>
        </form>
    </div>
</div>

<script>
    // Live Search Table
    function liveSearchPengajuan() {
        const query = document.getElementById('searchInput').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.pengajuan-row');
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

        const noMatch = document.getElementById('noMatchRow');
        if (noMatch) {
            if (visibleCount === 0 && rows.length > 0) {
                noMatch.classList.remove('hidden');
            } else {
                noMatch.classList.add('hidden');
            }
        }
    }

    // Toggle Filter
    function toggleFilterDropdown() {
        document.getElementById('filterDropdown').classList.toggle('hidden');
    }
    document.addEventListener('click', function(e) {
        const btn = document.getElementById('filterBtn');
        const drop = document.getElementById('filterDropdown');
        if (btn && drop && !btn.contains(e.target) && !drop.contains(e.target)) {
            drop.classList.add('hidden');
        }
    });

    // Modal Lampiran PDF
    function openLampiranModal(pengajuanId, nama) {
        document.getElementById('lampiran_owner').innerText = nama;
        const container = document.getElementById('lampiranList');
        container.innerHTML = '<div class="py-6 text-center text-gray-400 text-xs">Memuat dokumen...</div>';
        document.getElementById('modalLampiran').classList.remove('hidden');

        fetch("<?= base_url('admin/pengajuan/lampiran') ?>/" + pengajuanId)
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success' && res.data && res.data.length > 0) {
                    let html = '';
                    res.data.forEach(item => {
                        html += `
                            <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-gray-100/70 transition">
                                <div class="flex items-center gap-2.5 truncate mr-2">
                                    <span class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 font-bold text-[10px]">PDF</span>
                                    <span class="text-xs font-semibold text-gray-800 truncate">${item.nama_dokumen}</span>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="${item.file_url}" target="_blank" 
                                       class="px-3 py-1 rounded-full bg-emerald-100 hover:bg-emerald-200 text-[#33634B] text-[11px] font-bold transition">
                                        Lihat
                                    </a>
                                    <a href="${item.download_url}" 
                                       class="px-3 py-1 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 text-[11px] font-bold transition">
                                        Unduh
                                    </a>
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="py-6 text-center text-gray-400 text-xs">Tidak ada dokumen lampiran yang diunggah.</div>';
                }
            })
            .catch(() => {
                container.innerHTML = '<div class="py-6 text-center text-rose-500 text-xs">Gagal memuat dokumen lampiran.</div>';
            });
    }
    function closeLampiranModal() {
        document.getElementById('modalLampiran').classList.add('hidden');
    }

    // Modal Hapus Pengajuan
    function openHapusPengajuanModal(id, nama) {
        document.getElementById('hapus_pengajuan_nama').innerText = nama;
        document.getElementById('formHapusPengajuan').action = "<?= base_url('admin/pengajuan/delete') ?>/" + id;
        document.getElementById('modalHapusPengajuan').classList.remove('hidden');
    }
    function closeHapusPengajuanModal() {
        document.getElementById('modalHapusPengajuan').classList.add('hidden');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLampiranModal();
            closeHapusPengajuanModal();
        }
    });
</script>

<?= $this->endSection() ?>
