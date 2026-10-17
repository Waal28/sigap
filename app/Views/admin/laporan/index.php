<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- TOP ACTION & SEARCH BAR -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 relative">
    <!-- Left: Document Icon + Title Laporan -->
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-800 shadow-sm shrink-0">
            <svg class="w-6 h-6 text-gray-800" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight leading-tight">Laporan</h2>
            <p class="text-xs text-gray-500 hidden sm:block">Riwayat seluruh usulan kenaikan gaji dan pangkat yang telah diproses</p>
        </div>
    </div>

    <!-- Right Controls: Search & Filter -->
    <div class="flex items-center gap-3">
        <!-- Search Input Bar -->
        <form action="<?= base_url('admin/laporan') ?>" method="GET" class="relative m-0 p-0 flex items-center">
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
                       onkeyup="liveSearchLaporan()"
                       placeholder="Cari Nama....." 
                       class="w-48 sm:w-64 bg-white rounded-full pl-5 pr-9 py-2 text-sm text-gray-700 placeholder-gray-400 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#33634B] shadow-sm transition">
                <?php if (! empty($keyword)): ?>
                    <a href="<?= base_url('admin/laporan' . (! empty($selectedJenis) ? '?jenis=' . urlencode($selectedJenis) : '')) ?>" 
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
                    title="Filter Laporan">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </button>

            <!-- Filter Dropdown Popover -->
            <div id="filterDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 p-4 text-xs">
                <div class="flex items-center justify-between pb-2 mb-3 border-b border-gray-100">
                    <span class="font-bold text-gray-800 uppercase tracking-wider">Filter Laporan</span>
                    <?php if (! empty($selectedJenis) || ! empty($selectedStatus)): ?>
                        <a href="<?= base_url('admin/laporan') ?>" class="text-rose-600 hover:underline">Reset</a>
                    <?php endif; ?>
                </div>

                <!-- Opsi Kenaikan -->
                <div class="mb-3">
                    <label class="block font-bold text-gray-600 mb-1">Jenis Kenaikan:</label>
                    <div class="flex gap-1.5">
                        <a href="<?= base_url('admin/laporan' . (! empty($selectedStatus) ? '?status=' . urlencode($selectedStatus) : '')) ?>" 
                           class="px-2.5 py-1 rounded-lg border text-center flex-1 <?= empty($selectedJenis) ? 'bg-[#33634B] text-white font-bold' : 'border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
                            Semua
                        </a>
                        <a href="<?= base_url('admin/laporan?jenis=Gaji' . (! empty($selectedStatus) ? '&status=' . urlencode($selectedStatus) : '')) ?>" 
                           class="px-2.5 py-1 rounded-lg border text-center flex-1 <?= $selectedJenis === 'Gaji' ? 'bg-[#33634B] text-white font-bold' : 'border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
                            Gaji
                        </a>
                        <a href="<?= base_url('admin/laporan?jenis=Pangkat' . (! empty($selectedStatus) ? '&status=' . urlencode($selectedStatus) : '')) ?>" 
                           class="px-2.5 py-1 rounded-lg border text-center flex-1 <?= $selectedJenis === 'Pangkat' ? 'bg-[#33634B] text-white font-bold' : 'border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
                            Pangkat
                        </a>
                    </div>
                </div>

                <!-- Opsi Status -->
                <div>
                    <label class="block font-bold text-gray-600 mb-1">Status Verifikasi:</label>
                    <div class="space-y-1">
                        <a href="<?= base_url('admin/laporan' . (! empty($selectedJenis) ? '?jenis=' . urlencode($selectedJenis) : '')) ?>" 
                           class="block px-3 py-1.5 rounded-lg <?= empty($selectedStatus) ? 'bg-gray-100 text-gray-900 font-bold' : 'text-gray-600 hover:bg-gray-50' ?>">
                            Semua (Selesai Diproses)
                        </a>
                        <a href="<?= base_url('admin/laporan?status=Disetujui' . (! empty($selectedJenis) ? '&jenis=' . urlencode($selectedJenis) : '')) ?>" 
                           class="block px-3 py-1.5 rounded-lg <?= $selectedStatus === 'Disetujui' ? 'bg-emerald-100 text-emerald-900 font-bold' : 'text-gray-600 hover:bg-gray-50' ?>">
                            ✓ Disetujui
                        </a>
                        <a href="<?= base_url('admin/laporan?status=Ditolak' . (! empty($selectedJenis) ? '&jenis=' . urlencode($selectedJenis) : '')) ?>" 
                           class="block px-3 py-1.5 rounded-lg <?= $selectedStatus === 'Ditolak' ? 'bg-rose-100 text-rose-900 font-bold' : 'text-gray-600 hover:bg-gray-50' ?>">
                            ✕ Ditolak
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DATA TABLE (EXACT LAPORAN ADMIN & PEJABAT DESIGN) -->
<div class="bg-white/80 backdrop-blur-sm rounded-[28px] overflow-hidden shadow-sm border border-emerald-100">
    <div class="overflow-x-auto">
        <table id="tableLaporan" class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="border-b border-gray-200/80 text-gray-800 font-bold">
                    <th class="py-4 px-4 text-center w-12">No.</th>
                    <th class="py-4 px-4">Nama</th>
                    <th class="py-4 px-4">NIP</th>
                    <th class="py-4 px-4">Jabatan</th>
                    <th class="py-4 px-4">Masa Kerja</th>
                    <th class="py-4 px-4">Kenaikan</th>
                    <th class="py-4 px-4 text-center">Lampiran</th>
                    <th class="py-4 px-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody id="laporanTableBody" class="divide-y divide-gray-100 text-gray-600 font-medium">
                <?php if (! empty($pengajuans)): ?>
                    <?php $no = 1; foreach ($pengajuans as $row): ?>
                        <tr class="laporan-row hover:bg-emerald-50/60 transition duration-150"
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
                            <!-- Kolom Status (Teks Sesuai Desain: Disetujui / Ditolak) -->
                            <td class="py-3.5 px-4 text-center">
                                <?php if ($row['status'] === 'Disetujui'): ?>
                                    <span class="font-semibold text-emerald-700">Disetujui</span>
                                <?php elseif ($row['status'] === 'Ditolak'): ?>
                                    <span class="font-semibold text-rose-700" title="<?= esc($row['catatan'] ?? '') ?>">Ditolak</span>
                                <?php else: ?>
                                    <span class="font-semibold text-amber-700"><?= esc($row['status'] ?? '-') ?></span>
                                <?php endif; ?>
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
                                <p class="font-semibold text-gray-600">Belum ada data laporan pengajuan</p>
                                <p class="text-xs text-gray-400 mt-1">Data yang tampil adalah pengajuan yang telah selesai diproses oleh Pejabat Penilai.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DAFTAR LAMPIRAN BERKAS PDF           -->
<!-- ========================================== -->
<div id="lampiranModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-gray-100 transform transition-all">
        <div class="bg-[#33634B] text-white px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-black/30 flex items-center justify-center font-bold text-xs tracking-wider">
                    PDF
                </div>
                <div>
                    <h3 class="font-bold text-base leading-tight">Dokumen Lampiran Pengajuan</h3>
                    <p class="text-[11px] text-emerald-100 truncate max-w-xs" id="lampiranModalSubtitle">Nama Pegawai</p>
                </div>
            </div>
            <button type="button" onclick="closeLampiranModal()" class="text-emerald-100 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-6">
            <div id="lampiranLoading" class="py-8 text-center text-gray-500">
                <svg class="animate-spin h-6 w-6 text-[#33634B] mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <p class="text-xs">Memuat berkas lampiran...</p>
            </div>

            <div id="lampiranList" class="hidden space-y-2.5 max-h-80 overflow-y-auto pr-1">
                <!-- Diisi via AJAX JavaScript -->
            </div>

            <div id="lampiranEmpty" class="hidden py-8 text-center text-gray-400">
                <svg class="w-10 h-10 mx-auto text-gray-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-xs font-semibold text-gray-500">Tidak ada lampiran dokumen untuk pengajuan ini.</p>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex justify-end">
            <button type="button" onclick="closeLampiranModal()" 
                    class="px-5 py-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    // Live Search Client-side
    function liveSearchLaporan() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.laporan-row');

        rows.forEach(row => {
            const nama = (row.getAttribute('data-nama') || '').toLowerCase();
            const nip = (row.getAttribute('data-nip') || '').toLowerCase();
            const jabatan = (row.getAttribute('data-jabatan') || '').toLowerCase();
            const kenaikan = (row.getAttribute('data-kenaikan') || '').toLowerCase();
            const status = (row.getAttribute('data-status') || '').toLowerCase();

            if (nama.includes(filter) || nip.includes(filter) || jabatan.includes(filter) || kenaikan.includes(filter) || status.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Toggle filter popover
    function toggleFilterDropdown() {
        const dropdown = document.getElementById('filterDropdown');
        dropdown.classList.toggle('hidden');
    }

    // Close dropdown on outside click
    document.addEventListener('click', function(event) {
        const btn = document.getElementById('filterBtn');
        const dropdown = document.getElementById('filterDropdown');
        if (btn && dropdown && !btn.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Lampiran Modal
    function openLampiranModal(pengajuanId, namaPegawai) {
        document.getElementById('lampiranModalSubtitle').innerText = 'Pegawai: ' + namaPegawai;
        document.getElementById('lampiranLoading').classList.remove('hidden');
        document.getElementById('lampiranList').classList.add('hidden');
        document.getElementById('lampiranEmpty').classList.add('hidden');
        document.getElementById('lampiranModal').classList.remove('hidden');

        fetch('<?= base_url('lampiran/list') ?>/' + pengajuanId)
            .then(res => res.json())
            .then(data => {
                document.getElementById('lampiranLoading').classList.add('hidden');
                const listEl = document.getElementById('lampiranList');
                listEl.innerHTML = '';

                if (data.status === 'success' && data.data && data.data.length > 0) {
                    data.data.forEach(item => {
                        const itemDiv = document.createElement('div');
                        itemDiv.className = 'flex items-center justify-between p-3 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-emerald-50/50 transition';
                        itemDiv.innerHTML = `
                            <div class="flex items-center gap-3 overflow-hidden pr-2">
                                <div class="w-8 h-8 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0 font-bold text-[10px]">
                                    PDF
                                </div>
                                <span class="text-xs font-semibold text-gray-800 truncate">${item.nama_dokumen}</span>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <a href="${item.view_url}" target="_blank" 
                                   class="px-3 py-1 rounded-full bg-[#33634B] hover:bg-[#264D39] text-white text-[11px] font-semibold transition">
                                    Lihat
                                </a>
                                <a href="${item.download_url}" 
                                   class="px-2.5 py-1 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 text-[11px] font-semibold transition"
                                   title="Unduh Berkas">
                                    ↓
                                </a>
                            </div>
                        `;
                        listEl.appendChild(itemDiv);
                    });
                    listEl.classList.remove('hidden');
                } else {
                    document.getElementById('lampiranEmpty').classList.remove('hidden');
                }
            })
            .catch(err => {
                document.getElementById('lampiranLoading').classList.add('hidden');
                document.getElementById('lampiranEmpty').classList.remove('hidden');
            });
    }

    function closeLampiranModal() {
        document.getElementById('lampiranModal').classList.add('hidden');
    }
</script>

<?= $this->endSection() ?>
