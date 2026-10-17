<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- TOP ACTION & SEARCH BAR -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <!-- Title -->
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-800 shadow-sm">
            <svg class="w-6 h-6 rotate-45 -mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 tracking-tight">Pengajuan Kenaikan</h2>
    </div>

    <!-- Right Controls: Search & Filter -->
    <div class="flex items-center gap-3">
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
                    <th class="py-4 px-4">Jabatan</th>
                    <th class="py-4 px-4">Masa Kerja</th>
                    <th class="py-4 px-4">Kenaikan</th>
                    <th class="py-4 px-4 text-center">Lampiran</th>
                    <th class="py-4 px-4 text-center w-36">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-600 font-medium">
                <tr>
                    <td class="py-3.5 px-4 text-center">1</td>
                    <td class="py-3.5 px-4 font-semibold text-gray-800">Budi Santoso</td>
                    <td class="py-3.5 px-4">199502152020121002</td>
                    <td class="py-3.5 px-4">Gol II/A</td>
                    <td class="py-3.5 px-4">2 Tahun</td>
                    <td class="py-3.5 px-4">Gaji</td>
                    <td class="py-3.5 px-4 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-black text-white">
                            PDF
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <!-- View -->
                            <button class="text-sky-500 hover:text-sky-700 transition" title="Lihat">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            <?php if (session()->get('role') === 'PEJABAT_PENILAI'): ?>
                                <!-- Approve -->
                                <button class="text-emerald-500 hover:text-emerald-700 transition" title="Setujui">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <!-- Reject -->
                                <button class="text-rose-500 hover:text-rose-700 transition" title="Tolak">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
