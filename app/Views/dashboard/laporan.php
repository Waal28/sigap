<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- TOP ACTION -->
<div class="flex items-center gap-3 mb-6">
    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-800 shadow-sm">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
        </svg>
    </div>
    <h2 class="text-xl font-bold text-gray-900 tracking-tight">Laporan</h2>
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
                    <th class="py-4 px-4 text-center">Status</th>
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
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                            Menunggu
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
