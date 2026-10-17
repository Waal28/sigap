<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- TOP ACTION & SEARCH BAR -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <!-- Title -->
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-800 shadow-sm">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 tracking-tight">Data Pegawai</h2>
    </div>

    <!-- Right Controls: Add Button, Search, Filter -->
    <div class="flex items-center gap-3">
        <!-- Add Button (Plus) -->
        <button type="button" 
                class="w-10 h-10 rounded-full bg-[#33634B] hover:bg-[#264D39] text-white flex items-center justify-center shadow-md transition active:scale-95"
                title="Tambah Pegawai">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
        </button>

        <!-- Search Input -->
        <div class="relative">
            <input type="text" 
                   placeholder="Cari Nama....." 
                   class="w-48 sm:w-64 bg-white rounded-full px-5 py-2 text-sm text-gray-700 placeholder-gray-400 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#33634B] shadow-sm">
        </div>

        <!-- Filter Button -->
        <button type="button" 
                class="w-10 h-10 rounded-full bg-[#33634B] hover:bg-[#264D39] text-white flex items-center justify-center shadow-md transition active:scale-95"
                title="Filter Data">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
        </button>
    </div>
</div>

<!-- DATA TABLE -->
<div class="bg-white/80 backdrop-blur-sm rounded-2xl overflow-hidden shadow-sm border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="border-b border-gray-200 text-gray-700 font-bold">
                    <th class="py-4 px-4 text-center w-12">No.</th>
                    <th class="py-4 px-4">Nama</th>
                    <th class="py-4 px-4">NIP</th>
                    <th class="py-4 px-4">Kata Sandi</th>
                    <th class="py-4 px-4">Jabatan</th>
                    <th class="py-4 px-4">Masa Kerja</th>
                    <th class="py-4 px-4 text-center w-36">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-600 font-medium">
                <?php if (! empty($pegawais)): ?>
                    <?php $no = 1; foreach ($pegawais as $p): ?>
                        <tr class="hover:bg-emerald-50/50 transition">
                            <td class="py-3.5 px-4 text-center"><?= $no++ ?></td>
                            <td class="py-3.5 px-4 font-semibold text-gray-800"><?= esc($p->nama) ?></td>
                            <td class="py-3.5 px-4"><?= esc($p->nip) ?></td>
                            <td class="py-3.5 px-4 tracking-widest text-gray-500">*************</td>
                            <td class="py-3.5 px-4"><?= esc($p->jabatan) ?></td>
                            <td class="py-3.5 px-4"><?= esc($p->masa_kerja) ?></td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-2.5">
                                    <!-- View Detail Icon -->
                                    <button class="text-sky-500 hover:text-sky-700 transition" title="Lihat">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <!-- Edit Icon -->
                                    <button class="text-amber-500 hover:text-amber-700 transition" title="Ubah">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <!-- Delete Icon -->
                                    <button class="text-rose-500 hover:text-rose-700 transition" title="Hapus">
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
                        <td colspan="7" class="py-8 text-center text-gray-500">
                            Belum ada data pegawai. Silakan tambahkan data melalui seeder atau tombol tambah.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
