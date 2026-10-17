<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- TOP ACTION & SEARCH BAR -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 relative">
    <!-- Left: Icon User + Page Title -->
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-800 shadow-sm shrink-0">
            <svg class="w-6 h-6 text-gray-800" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight leading-tight">Data Pegawai</h2>
            <p class="text-xs text-gray-500 hidden sm:block">Kelola informasi pegawai dan akun login SIGAP</p>
        </div>
    </div>

    <!-- Right Controls: Tambah (+), Search Bar, Filter Funnel -->
    <div class="flex items-center gap-3">
        <!-- Tombol Tambah (+) Lingkaran Hijau -->
        <button type="button" 
                onclick="openTambahModal()"
                class="w-10 h-10 rounded-full bg-[#33634B] hover:bg-[#264D39] text-white flex items-center justify-center shadow-md transition-all transform active:scale-95 shrink-0"
                title="Tambah Pegawai Baru">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
        </button>

        <!-- Search Input Bar (Live search & query form) -->
        <form action="<?= base_url('admin/pegawai') ?>" method="GET" class="relative m-0 p-0 flex items-center">
            <?php if (! empty($selectedJabatan)): ?>
                <input type="hidden" name="jabatan" value="<?= esc($selectedJabatan) ?>">
            <?php endif; ?>
            <div class="relative">
                <input type="text" 
                       id="searchInput"
                       name="q"
                       value="<?= esc($keyword ?? '') ?>"
                       onkeyup="liveSearch()"
                       placeholder="Cari Nama....." 
                       class="w-48 sm:w-64 bg-white rounded-full pl-5 pr-9 py-2 text-sm text-gray-700 placeholder-gray-400 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#33634B] shadow-sm transition">
                <?php if (! empty($keyword)): ?>
                    <a href="<?= base_url('admin/pegawai' . (! empty($selectedJabatan) ? '?jabatan=' . urlencode($selectedJabatan) : '')) ?>" 
                       class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600" title="Hapus Pencarian">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <!-- Tombol Filter Funnel -->
        <div class="relative">
            <button type="button" 
                    id="filterBtn"
                    onclick="toggleFilterDropdown()"
                    class="w-10 h-10 rounded-full bg-[#33634B] hover:bg-[#264D39] text-white flex items-center justify-center shadow-md transition-all transform active:scale-95 shrink-0 <?= ! empty($selectedJabatan) ? 'ring-2 ring-emerald-300 bg-[#264D39]' : '' ?>"
                    title="Filter Jabatan">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </button>

            <!-- Filter Dropdown Popover -->
            <div id="filterDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 p-3 text-sm">
                <div class="flex items-center justify-between pb-2 mb-2 border-b border-gray-100">
                    <span class="font-bold text-gray-800 text-xs uppercase tracking-wider">Filter Jabatan</span>
                    <?php if (! empty($selectedJabatan)): ?>
                        <a href="<?= base_url('admin/pegawai' . (! empty($keyword) ? '?q=' . urlencode($keyword) : '')) ?>" 
                           class="text-xs text-rose-600 hover:underline">Reset</a>
                    <?php endif; ?>
                </div>
                <div class="max-h-56 overflow-y-auto space-y-1">
                    <a href="<?= base_url('admin/pegawai' . (! empty($keyword) ? '?q=' . urlencode($keyword) : '')) ?>" 
                       class="block px-3 py-1.5 rounded-xl text-xs font-medium <?= empty($selectedJabatan) ? 'bg-emerald-50 text-[#33634B] font-bold' : 'text-gray-600 hover:bg-gray-50' ?>">
                        Semua Jabatan
                    </a>
                    <?php if (! empty($jabatans)): ?>
                        <?php foreach ($jabatans as $jb): ?>
                            <a href="<?= base_url('admin/pegawai?jabatan=' . urlencode($jb) . (! empty($keyword) ? '&q=' . urlencode($keyword) : '')) ?>" 
                               class="block px-3 py-1.5 rounded-xl text-xs truncate <?= $selectedJabatan === $jb ? 'bg-emerald-50 text-[#33634B] font-bold' : 'text-gray-600 hover:bg-gray-50' ?>">
                                <?= esc($jb) ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- VALIDATION ERRORS BANNER -->
<?php if (session()->getFlashdata('errors')): ?>
    <div class="mb-5 bg-rose-50 border border-rose-300 text-rose-800 px-5 py-4 rounded-2xl shadow-sm text-sm">
        <div class="flex items-center gap-2 font-bold mb-1">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>Terdapat beberapa kesalahan input:</span>
        </div>
        <ul class="list-disc list-inside text-xs space-y-1 pl-1">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- ACTIVE FILTER BADGE -->
<?php if (! empty($selectedJabatan)): ?>
    <div class="mb-4 flex items-center gap-2 text-xs text-gray-600">
        <span>Menampilkan pegawai dengan jabatan:</span>
        <span class="inline-flex items-center gap-1.5 bg-emerald-100 text-emerald-800 font-semibold px-3 py-1 rounded-full">
            <?= esc($selectedJabatan) ?>
            <a href="<?= base_url('admin/pegawai' . (! empty($keyword) ? '?q=' . urlencode($keyword) : '')) ?>" class="hover:text-rose-600">
                &times;
            </a>
        </span>
    </div>
<?php endif; ?>

<!-- DATA TABLE CONTAINER (ROUNDED CORNERS WITH MINT COLOR) -->
<div class="bg-white/80 backdrop-blur-sm rounded-[28px] overflow-hidden shadow-sm border border-emerald-100">
    <div class="overflow-x-auto">
        <table id="pegawaiTable" class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="border-b border-gray-200/80 text-gray-800 font-bold">
                    <th class="py-4 px-4 text-center w-12">No.</th>
                    <th class="py-4 px-4">Nama</th>
                    <th class="py-4 px-4">NIP</th>
                    <th class="py-4 px-4">Kata Sandi</th>
                    <th class="py-4 px-4">Jabatan</th>
                    <th class="py-4 px-4">Masa Kerja</th>
                    <th class="py-4 px-4 text-center w-36">Aksi</th>
                </tr>
            </thead>
            <tbody id="pegawaiTableBody" class="divide-y divide-gray-100 text-gray-600 font-medium">
                <?php if (! empty($pegawais)): ?>
                    <?php $no = 1; foreach ($pegawais as $p): ?>
                        <tr class="pegawai-row hover:bg-emerald-50/60 transition duration-150"
                            data-id="<?= $p->id ?>"
                            data-user-id="<?= $p->user_id ?>"
                            data-nama="<?= esc($p->nama, 'attr') ?>"
                            data-nip="<?= esc($p->nip, 'attr') ?>"
                            data-jabatan="<?= esc($p->jabatan, 'attr') ?>"
                            data-masa-kerja="<?= esc($p->masa_kerja, 'attr') ?>"
                            data-created-at="<?= $p->created_at ? esc((string)$p->created_at, 'attr') : '-' ?>">
                            <td class="py-3.5 px-4 text-center row-number font-normal"><?= $no++ ?></td>
                            <td class="py-3.5 px-4 font-semibold text-gray-800 row-nama"><?= esc($p->nama) ?></td>
                            <td class="py-3.5 px-4 row-nip"><?= esc($p->nip) ?></td>
                            <td class="py-3.5 px-4 tracking-widest text-gray-500 font-mono select-none">************</td>
                            <td class="py-3.5 px-4 row-jabatan"><?= esc($p->jabatan) ?></td>
                            <td class="py-3.5 px-4 row-masa-kerja"><?= esc($p->masa_kerja) ?></td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <!-- Detail (Eye icon in blue) -->
                                    <button type="button" 
                                            onclick="openDetailModal(<?= $p->id ?>)"
                                            class="text-sky-500 hover:text-sky-700 transition transform hover:scale-110" 
                                            title="Detail Pegawai">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    <!-- Edit (Pencil icon in olive/yellow) -->
                                    <button type="button" 
                                            onclick="openEditModal(<?= $p->id ?>)"
                                            class="text-[#8FA838] hover:text-[#6e8229] transition transform hover:scale-110" 
                                            title="Ubah Data">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <!-- Delete (Trash icon in red) -->
                                    <button type="button" 
                                            onclick="openHapusModal(<?= $p->id ?>, '<?= esc($p->nama, 'js') ?>')"
                                            class="text-[#C0392B] hover:text-red-700 transition transform hover:scale-110" 
                                            title="Hapus Pegawai">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="emptyRow">
                        <td colspan="7" class="py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="font-semibold text-gray-600">Tidak ada data pegawai ditemukan</p>
                                <p class="text-xs text-gray-400 mt-1">Klik tombol (+) di atas untuk menambahkan data pegawai baru.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                <!-- Hidden row when live search finds 0 matches -->
                <tr id="noMatchRow" class="hidden">
                    <td colspan="7" class="py-8 text-center text-gray-500 font-medium">
                        Tidak ada data pegawai yang cocok dengan kata kunci pencarian.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL TAMBAH PEGAWAI                      -->
<!-- ========================================== -->
<div id="modalTambah" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all">
        <!-- Header Modal -->
        <div class="bg-[#33634B] px-6 py-4 flex items-center justify-between text-white">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <h3 class="font-bold text-base">Tambah Data Pegawai</h3>
            </div>
            <button type="button" onclick="closeTambahModal()" class="text-white/80 hover:text-white text-xl font-bold leading-none focus:outline-none">
                &times;
            </button>
        </div>

        <!-- Form Tambah -->
        <form action="<?= base_url('admin/pegawai/store') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>

            <!-- Nama Lengkap -->
            <div>
                <label for="tambah_nama" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="tambah_nama" 
                       name="nama" 
                       value="<?= old('nama') ?>"
                       required
                       placeholder="Contoh: Budi Santoso, S.Kom" 
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#33634B]">
            </div>

            <!-- NIP -->
            <div>
                <label for="tambah_nip" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Nomor Induk Pegawai (NIP) <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="tambah_nip" 
                       name="nip" 
                       value="<?= old('nip') ?>"
                       required
                       placeholder="Contoh: 199502152020121002" 
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#33634B]">
            </div>

            <!-- Kata Sandi Akun -->
            <div>
                <label for="tambah_password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Kata Sandi Akun <span class="text-rose-500">*</span>
                </label>
                <div class="relative flex items-center">
                    <input type="password" 
                           id="tambah_password" 
                           name="password" 
                           required
                           minlength="6"
                           placeholder="Minimal 6 karakter" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#33634B] pr-10">
                    <button type="button" 
                            onclick="toggleInputVisibility('tambah_password', this)" 
                            class="absolute right-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Jabatan / Golongan -->
            <div>
                <label for="tambah_jabatan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Jabatan / Golongan <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="tambah_jabatan" 
                       name="jabatan" 
                       list="list_jabatan_tambah"
                       value="<?= old('jabatan') ?>"
                       required
                       placeholder="Contoh: Gol II/A atau Kepala Dinas" 
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#33634B]">
                <datalist id="list_jabatan_tambah">
                    <option value="Gol I/A">
                    <option value="Gol I/B">
                    <option value="Gol I/C">
                    <option value="Gol I/D">
                    <option value="Gol II/A">
                    <option value="Gol II/B">
                    <option value="Gol II/C">
                    <option value="Gol II/D">
                    <option value="Gol III/A">
                    <option value="Gol III/B">
                    <option value="Gol III/C">
                    <option value="Gol III/D">
                    <option value="Gol IV/A">
                    <option value="Gol IV/B">
                    <option value="Gol IV/C">
                    <option value="Kepala Dinas">
                    <option value="Sekretaris">
                    <option value="Staff Pelaksana">
                </datalist>
            </div>

            <!-- Masa Kerja -->
            <div>
                <label for="tambah_masa_kerja" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Masa Kerja <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="tambah_masa_kerja" 
                       name="masa_kerja" 
                       value="<?= old('masa_kerja') ?>"
                       required
                       placeholder="Contoh: 2 Tahun" 
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#33634B]">
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" 
                        onclick="closeTambahModal()" 
                        class="px-5 py-2.5 rounded-full border border-gray-300 text-gray-700 text-xs font-semibold hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-full bg-[#33634B] hover:bg-[#264D39] text-white text-xs font-bold shadow-md transition transform active:scale-95">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL EDIT PEGAWAI                        -->
<!-- ========================================== -->
<div id="modalEdit" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all">
        <!-- Header Modal -->
        <div class="bg-[#33634B] px-6 py-4 flex items-center justify-between text-white">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-base">Ubah Data Pegawai</h3>
            </div>
            <button type="button" onclick="closeEditModal()" class="text-white/80 hover:text-white text-xl font-bold leading-none focus:outline-none">
                &times;
            </button>
        </div>

        <!-- Form Edit -->
        <form id="formEdit" action="" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>

            <!-- Nama Lengkap -->
            <div>
                <label for="edit_nama" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="edit_nama" 
                       name="nama" 
                       required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#33634B]">
            </div>

            <!-- NIP -->
            <div>
                <label for="edit_nip" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Nomor Induk Pegawai (NIP) <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="edit_nip" 
                       name="nip" 
                       required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#33634B]">
            </div>

            <!-- Kata Sandi Baru (Opsional) -->
            <div>
                <label for="edit_password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Kata Sandi Baru <span class="text-gray-400 font-normal">(Kosongkan jika tidak diubah)</span>
                </label>
                <div class="relative flex items-center">
                    <input type="password" 
                           id="edit_password" 
                           name="password" 
                           minlength="6"
                           placeholder="Masukkan kata sandi baru (opsional)" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#33634B] pr-10">
                    <button type="button" 
                            onclick="toggleInputVisibility('edit_password', this)" 
                            class="absolute right-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Jabatan / Golongan -->
            <div>
                <label for="edit_jabatan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Jabatan / Golongan <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="edit_jabatan" 
                       name="jabatan" 
                       list="list_jabatan_edit"
                       required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#33634B]">
                <datalist id="list_jabatan_edit">
                    <option value="Gol I/A">
                    <option value="Gol I/B">
                    <option value="Gol I/C">
                    <option value="Gol I/D">
                    <option value="Gol II/A">
                    <option value="Gol II/B">
                    <option value="Gol II/C">
                    <option value="Gol II/D">
                    <option value="Gol III/A">
                    <option value="Gol III/B">
                    <option value="Gol III/C">
                    <option value="Gol III/D">
                    <option value="Gol IV/A">
                    <option value="Gol IV/B">
                    <option value="Gol IV/C">
                    <option value="Kepala Dinas">
                    <option value="Sekretaris">
                    <option value="Staff Pelaksana">
                </datalist>
            </div>

            <!-- Masa Kerja -->
            <div>
                <label for="edit_masa_kerja" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Masa Kerja <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="edit_masa_kerja" 
                       name="masa_kerja" 
                       required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#33634B]">
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" 
                        onclick="closeEditModal()" 
                        class="px-5 py-2.5 rounded-full border border-gray-300 text-gray-700 text-xs font-semibold hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-full bg-[#33634B] hover:bg-[#264D39] text-white text-xs font-bold shadow-md transition transform active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DETAIL PEGAWAI                      -->
<!-- ========================================== -->
<div id="modalDetail" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all">
        <!-- Header Modal -->
        <div class="bg-[#33634B] px-6 py-4 flex items-center justify-between text-white">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-base">Detail Pegawai</h3>
            </div>
            <button type="button" onclick="closeDetailModal()" class="text-white/80 hover:text-white text-xl font-bold leading-none focus:outline-none">
                &times;
            </button>
        </div>

        <!-- Detail Body -->
        <div class="p-6">
            <!-- Profile Avatar & Name -->
            <div class="flex items-center gap-4 pb-5 border-b border-gray-100">
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-[#33634B] flex items-center justify-center font-bold text-xl shrink-0 shadow-inner">
                    <span id="detail_initial">P</span>
                </div>
                <div>
                    <h4 id="detail_nama" class="font-extrabold text-base text-gray-900 leading-snug">Nama Pegawai</h4>
                    <span id="detail_jabatan_badge" class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                        Golongan
                    </span>
                </div>
            </div>

            <!-- Details List -->
            <div class="mt-4 space-y-3 text-xs">
                <div class="flex justify-between py-1.5 border-b border-gray-50">
                    <span class="text-gray-500 font-medium">Nomor Induk Pegawai (NIP):</span>
                    <span id="detail_nip" class="font-bold text-gray-800 font-mono">-</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-gray-50">
                    <span class="text-gray-500 font-medium">Jabatan / Golongan:</span>
                    <span id="detail_jabatan" class="font-bold text-gray-800">-</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-gray-50">
                    <span class="text-gray-500 font-medium">Masa Kerja:</span>
                    <span id="detail_masa_kerja" class="font-bold text-gray-800">-</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-gray-50">
                    <span class="text-gray-500 font-medium">Hak Akses / Role:</span>
                    <span id="detail_role" class="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">PEGAWAI</span>
                </div>
                <div class="flex justify-between py-1.5">
                    <span class="text-gray-500 font-medium">Terdaftar Sejak:</span>
                    <span id="detail_created_at" class="font-bold text-gray-800">-</span>
                </div>
            </div>

            <!-- Close Button -->
            <div class="mt-6 flex justify-end">
                <button type="button" 
                        onclick="closeDetailModal()" 
                        class="px-6 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL HAPUS PEGAWAI                       -->
<!-- ========================================== -->
<div id="modalHapus" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl overflow-hidden p-6 text-center transform transition-all">
        <!-- Warning Icon -->
        <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-600 mx-auto flex items-center justify-center mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <h3 class="text-base font-bold text-gray-900 mb-2">Hapus Data Pegawai?</h3>
        <p class="text-xs text-gray-500 mb-6">
            Apakah Anda yakin ingin menghapus data <span id="hapus_nama_text" class="font-bold text-gray-800"></span>? Akun pengguna terkait juga akan dihapus secara permanen dari sistem SIGAP.
        </p>

        <form id="formHapus" action="" method="POST" class="flex items-center justify-center gap-3">
            <?= csrf_field() ?>
            <button type="button" 
                    onclick="closeHapusModal()" 
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

<!-- ========================================== -->
<!-- JAVASCRIPT LOGIC                           -->
<!-- ========================================== -->
<script>
    // --- 1. Client-Side Live Search ---
    function liveSearch() {
        const query = document.getElementById('searchInput').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.pegawai-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const nama = row.getAttribute('data-nama').toLowerCase();
            const nip = row.getAttribute('data-nip').toLowerCase();
            const jabatan = row.getAttribute('data-jabatan').toLowerCase();
            const masaKerja = row.getAttribute('data-masa-kerja').toLowerCase();

            if (nama.includes(query) || nip.includes(query) || jabatan.includes(query) || masaKerja.includes(query)) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        const noMatchRow = document.getElementById('noMatchRow');
        if (noMatchRow) {
            if (visibleCount === 0 && rows.length > 0) {
                noMatchRow.classList.remove('hidden');
            } else {
                noMatchRow.classList.add('hidden');
            }
        }
    }

    // --- 2. Filter Dropdown Toggle ---
    function toggleFilterDropdown() {
        const dropdown = document.getElementById('filterDropdown');
        dropdown.classList.toggle('hidden');
    }

    // Tutup filter dropdown jika klik di luar
    document.addEventListener('click', function(e) {
        const filterBtn = document.getElementById('filterBtn');
        const filterDropdown = document.getElementById('filterDropdown');
        if (filterBtn && filterDropdown && !filterBtn.contains(e.target) && !filterDropdown.contains(e.target)) {
            filterDropdown.classList.add('hidden');
        }
    });

    // --- 3. Toggle Password Input Visibility ---
    function toggleInputVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            btn.classList.add('text-[#33634B]');
            btn.classList.remove('text-gray-400');
        } else {
            input.type = 'password';
            btn.classList.remove('text-[#33634B]');
            btn.classList.add('text-gray-400');
        }
    }

    // --- 4. Modal Tambah ---
    function openTambahModal() {
        document.getElementById('modalTambah').classList.remove('hidden');
    }
    function closeTambahModal() {
        document.getElementById('modalTambah').classList.add('hidden');
    }

    // --- 5. Modal Edit ---
    function openEditModal(id) {
        const row = document.querySelector(`.pegawai-row[data-id="${id}"]`);
        if (!row) return;

        const nama = row.getAttribute('data-nama');
        const nip = row.getAttribute('data-nip');
        const jabatan = row.getAttribute('data-jabatan');
        const masaKerja = row.getAttribute('data-masa-kerja');

        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_nip').value = nip;
        document.getElementById('edit_jabatan').value = jabatan;
        document.getElementById('edit_masa_kerja').value = masaKerja;
        document.getElementById('edit_password').value = '';

        document.getElementById('formEdit').action = "<?= base_url('admin/pegawai/update') ?>/" + id;
        document.getElementById('modalEdit').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('modalEdit').classList.add('hidden');
    }

    // --- 6. Modal Detail ---
    function openDetailModal(id) {
        const row = document.querySelector(`.pegawai-row[data-id="${id}"]`);
        if (!row) return;

        const nama = row.getAttribute('data-nama');
        const nip = row.getAttribute('data-nip');
        const jabatan = row.getAttribute('data-jabatan');
        const masaKerja = row.getAttribute('data-masa-kerja');
        const createdAt = row.getAttribute('data-created-at');

        document.getElementById('detail_nama').innerText = nama;
        document.getElementById('detail_initial').innerText = nama.charAt(0).toUpperCase();
        document.getElementById('detail_nip').innerText = nip;
        document.getElementById('detail_jabatan').innerText = jabatan;
        document.getElementById('detail_jabatan_badge').innerText = jabatan;
        document.getElementById('detail_masa_kerja').innerText = masaKerja;
        document.getElementById('detail_created_at').innerText = createdAt;

        document.getElementById('modalDetail').classList.remove('hidden');
    }
    function closeDetailModal() {
        document.getElementById('modalDetail').classList.add('hidden');
    }

    // --- 7. Modal Hapus ---
    function openHapusModal(id, nama) {
        document.getElementById('hapus_nama_text').innerText = nama;
        document.getElementById('formHapus').action = "<?= base_url('admin/pegawai/delete') ?>/" + id;
        document.getElementById('modalHapus').classList.remove('hidden');
    }
    function closeHapusModal() {
        document.getElementById('modalHapus').classList.add('hidden');
    }

    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTambahModal();
            closeEditModal();
            closeDetailModal();
            closeHapusModal();
        }
    });
</script>

<?= $this->endSection() ?>
