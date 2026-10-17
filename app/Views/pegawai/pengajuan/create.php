<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- PAGE HEADER -->
<div class="flex items-center gap-3 mb-6">
    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-800 shadow-sm shrink-0">
        <svg class="w-6 h-6 text-gray-800" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
        </svg>
    </div>
    <div>
        <h2 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight leading-tight">Pengajuan Kenaikan</h2>
        <p class="text-xs text-gray-500">Lengkapi formulir dan unggah dokumen persyaratan dalam format PDF (maks. 2MB per berkas)</p>
    </div>
</div>

<!-- VALIDATION ERRORS BANNER -->
<?php if (session()->getFlashdata('errors')): ?>
    <div class="mb-5 bg-rose-50 border border-rose-300 text-rose-800 px-5 py-4 rounded-2xl shadow-sm text-sm">
        <div class="flex items-center gap-2 font-bold mb-1">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>Periksa kembali dokumen yang Anda unggah:</span>
        </div>
        <ul class="list-disc list-inside text-xs space-y-1 pl-1">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- FORM PENGAJUAN (EXACT 2-COLUMN DESIGN) -->
<form id="formPengajuan" action="<?= base_url('pegawai/pengajuan/store') ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
        
        <!-- =================== KOLOM KIRI =================== -->
        <div class="space-y-4">
            
            <!-- 1. Nama -->
            <div class="bg-white rounded-full px-5 py-3 shadow-sm border border-gray-100 flex items-center">
                <input type="text" 
                       name="nama" 
                       value="<?= esc($pegawai->nama ?? '') ?>" 
                       readonly
                       placeholder="Nama"
                       class="w-full bg-transparent text-sm font-medium text-gray-800 placeholder-gray-600 focus:outline-none cursor-default">
            </div>

            <!-- 2. Jabatan -->
            <div class="bg-white rounded-full px-5 py-3 shadow-sm border border-gray-100 flex items-center">
                <input type="text" 
                       name="jabatan" 
                       value="<?= esc($pegawai->jabatan ?? '') ?>" 
                       readonly
                       placeholder="Jabatan"
                       class="w-full bg-transparent text-sm font-medium text-gray-800 placeholder-gray-600 focus:outline-none cursor-default">
            </div>

            <!-- 3. Jenis Kenaikan (Dropdown / Selection) -->
            <div class="bg-white rounded-full px-5 py-3 shadow-sm border border-gray-100 flex items-center relative">
                <select name="jenis_kenaikan" 
                        required
                        class="w-full bg-transparent text-sm font-semibold text-gray-800 focus:outline-none cursor-pointer appearance-none">
                    <option value="" disabled <?= old('jenis_kenaikan') ? '' : 'selected' ?>>Jenis Kenaikan</option>
                    <option value="Gaji" <?= old('jenis_kenaikan') === 'Gaji' ? 'selected' : '' ?>>Kenaikan Gaji Berkala</option>
                    <option value="Pangkat" <?= old('jenis_kenaikan') === 'Pangkat' ? 'selected' : '' ?>>Kenaikan Pangkat</option>
                </select>
                <div class="pointer-events-none absolute right-5 text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

            <!-- 4. Surat Rekomendasi (PDF) -->
            <div class="bg-white rounded-full px-5 py-3 shadow-sm border border-gray-100 flex items-center justify-between cursor-pointer hover:border-emerald-300 transition"
                 onclick="document.getElementById('file_surat_rekomendasi').click()">
                <span class="text-sm font-medium text-gray-800 truncate" id="label_surat_rekomendasi">Surat Rekomendasi</span>
                <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600 shrink-0 ml-2 upload-badge">Pilih PDF</span>
                <input type="file" id="file_surat_rekomendasi" name="surat_rekomendasi" accept=".pdf" class="hidden"
                       onchange="updateFileName(this, 'label_surat_rekomendasi')">
            </div>

            <!-- 5. Daftar Riwayat Hidup (PDF) -->
            <div class="bg-white rounded-full px-5 py-3 shadow-sm border border-gray-100 flex items-center justify-between cursor-pointer hover:border-emerald-300 transition"
                 onclick="document.getElementById('file_daftar_riwayat_hidup').click()">
                <span class="text-sm font-medium text-gray-800 truncate" id="label_daftar_riwayat_hidup">Daftar Riwayat Hidup</span>
                <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600 shrink-0 ml-2 upload-badge">Pilih PDF</span>
                <input type="file" id="file_daftar_riwayat_hidup" name="daftar_riwayat_hidup" accept=".pdf" class="hidden"
                       onchange="updateFileName(this, 'label_daftar_riwayat_hidup')">
            </div>

            <!-- 6. Surat Pernyataan Tidak Sedang Dalam Hukuman Disiplin (PDF) -->
            <div class="bg-white rounded-full px-5 py-3 shadow-sm border border-gray-100 flex items-center justify-between cursor-pointer hover:border-emerald-300 transition"
                 onclick="document.getElementById('file_surat_pernyataan_disiplin').click()">
                <span class="text-sm font-medium text-gray-800 truncate" id="label_surat_pernyataan_disiplin">Surat Pernyataan Tidak Sedang Dalam Hukuman Disiplin</span>
                <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600 shrink-0 ml-2 upload-badge">Pilih PDF</span>
                <input type="file" id="file_surat_pernyataan_disiplin" name="surat_pernyataan_disiplin" accept=".pdf" class="hidden"
                       onchange="updateFileName(this, 'label_surat_pernyataan_disiplin')">
            </div>

            <!-- 7. Kartu Keluarga (PDF) -->
            <div class="bg-white rounded-full px-5 py-3 shadow-sm border border-gray-100 flex items-center justify-between cursor-pointer hover:border-emerald-300 transition"
                 onclick="document.getElementById('file_kartu_keluarga').click()">
                <span class="text-sm font-medium text-gray-800 truncate" id="label_kartu_keluarga">Kartu Keluarga</span>
                <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600 shrink-0 ml-2 upload-badge">Pilih PDF</span>
                <input type="file" id="file_kartu_keluarga" name="kartu_keluarga" accept=".pdf" class="hidden"
                       onchange="updateFileName(this, 'label_kartu_keluarga')">
            </div>

        </div>

        <!-- =================== KOLOM KANAN =================== -->
        <div class="space-y-4">
            
            <!-- 1. Nomor Induk Pegawai -->
            <div class="bg-white rounded-full px-5 py-3 shadow-sm border border-gray-100 flex items-center">
                <input type="text" 
                       name="nip" 
                       value="<?= esc($pegawai->nip ?? '') ?>" 
                       readonly
                       placeholder="Nomor Induk Pegawai"
                       class="w-full bg-transparent text-sm font-medium text-gray-800 placeholder-gray-600 focus:outline-none cursor-default font-mono">
            </div>

            <!-- 2. Masa Kerja -->
            <div class="bg-white rounded-full px-5 py-3 shadow-sm border border-gray-100 flex items-center">
                <input type="text" 
                       name="masa_kerja" 
                       value="<?= esc($pegawai->masa_kerja ?? '') ?>" 
                       readonly
                       placeholder="Masa Kerja"
                       class="w-full bg-transparent text-sm font-medium text-gray-800 placeholder-gray-600 focus:outline-none cursor-default">
            </div>

            <!-- 3. Scan SKP (PDF) -->
            <div class="bg-white rounded-full px-5 py-3 shadow-sm border border-gray-100 flex items-center justify-between cursor-pointer hover:border-emerald-300 transition"
                 onclick="document.getElementById('file_scan_skp').click()">
                <span class="text-sm font-medium text-gray-800 truncate" id="label_scan_skp">Scan SKP</span>
                <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600 shrink-0 ml-2 upload-badge">Pilih PDF</span>
                <input type="file" id="file_scan_skp" name="scan_skp" accept=".pdf" class="hidden"
                       onchange="updateFileName(this, 'label_scan_skp')">
            </div>

            <!-- 4. Fotokopi SK Terakhir (PDF) -->
            <div class="bg-white rounded-full px-5 py-3 shadow-sm border border-gray-100 flex items-center justify-between cursor-pointer hover:border-emerald-300 transition"
                 onclick="document.getElementById('file_sk_terakhir').click()">
                <span class="text-sm font-medium text-gray-800 truncate" id="label_sk_terakhir">Fotokopi SK Terakhir</span>
                <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600 shrink-0 ml-2 upload-badge">Pilih PDF</span>
                <input type="file" id="file_sk_terakhir" name="sk_terakhir" accept=".pdf" class="hidden"
                       onchange="updateFileName(this, 'label_sk_terakhir')">
            </div>

            <!-- 5. Fotokopi Ijazah & Transkrip Nilai (PDF) -->
            <div class="bg-white rounded-full px-5 py-3 shadow-sm border border-gray-100 flex items-center justify-between cursor-pointer hover:border-emerald-300 transition"
                 onclick="document.getElementById('file_ijazah_transkrip').click()">
                <span class="text-sm font-medium text-gray-800 truncate" id="label_ijazah_transkrip">Fotokopi Ijazah & Transkrip Nilai</span>
                <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600 shrink-0 ml-2 upload-badge">Pilih PDF</span>
                <input type="file" id="file_ijazah_transkrip" name="ijazah_transkrip" accept=".pdf" class="hidden"
                       onchange="updateFileName(this, 'label_ijazah_transkrip')">
            </div>

            <!-- 6. Surat Keterangan Sehat (PDF) -->
            <div class="bg-white rounded-full px-5 py-3 shadow-sm border border-gray-100 flex items-center justify-between cursor-pointer hover:border-emerald-300 transition"
                 onclick="document.getElementById('file_surat_keterangan_sehat').click()">
                <span class="text-sm font-medium text-gray-800 truncate" id="label_surat_keterangan_sehat">Surat Keterangan Sehat</span>
                <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600 shrink-0 ml-2 upload-badge">Pilih PDF</span>
                <input type="file" id="file_surat_keterangan_sehat" name="surat_keterangan_sehat" accept=".pdf" class="hidden"
                       onchange="updateFileName(this, 'label_surat_keterangan_sehat')">
            </div>

            <!-- 7. Bukti Pelatihan (PDF) -->
            <div class="bg-white rounded-full px-5 py-3 shadow-sm border border-gray-100 flex items-center justify-between cursor-pointer hover:border-emerald-300 transition"
                 onclick="document.getElementById('file_bukti_pelatihan').click()">
                <span class="text-sm font-medium text-gray-800 truncate" id="label_bukti_pelatihan">Bukti Pelatihan</span>
                <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600 shrink-0 ml-2 upload-badge">Pilih PDF</span>
                <input type="file" id="file_bukti_pelatihan" name="bukti_pelatihan" accept=".pdf" class="hidden"
                       onchange="updateFileName(this, 'label_bukti_pelatihan')">
            </div>

            <!-- 8. Formulir Usulan Kenaikan Pangkat (PDF) -->
            <div class="bg-white rounded-full px-5 py-3 shadow-sm border border-gray-100 flex items-center justify-between cursor-pointer hover:border-emerald-300 transition"
                 onclick="document.getElementById('file_formulir_usulan').click()">
                <span class="text-sm font-medium text-gray-800 truncate" id="label_formulir_usulan">Formulir Usulan Kenaikan Pangkat</span>
                <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600 shrink-0 ml-2 upload-badge">Pilih PDF</span>
                <input type="file" id="file_formulir_usulan" name="formulir_usulan" accept=".pdf" class="hidden"
                       onchange="updateFileName(this, 'label_formulir_usulan')">
            </div>

        </div>

    </div>

    <!-- TOMBOL AKSI: HAPUS & AJUKAN -->
    <div class="mt-8 flex items-center justify-center gap-4">
        <!-- Tombol Hapus (Reset) -->
        <button type="reset" 
                onclick="resetFormLabels()"
                class="bg-white hover:bg-gray-100 text-gray-900 font-bold px-12 py-2.5 rounded-full shadow-md hover:shadow-lg transition-all transform active:scale-95 text-sm tracking-wide">
            Hapus
        </button>

        <!-- Tombol Ajukan (Submit) -->
        <button type="submit" 
                class="bg-white hover:bg-gray-100 text-gray-900 font-bold px-12 py-2.5 rounded-full shadow-md hover:shadow-lg transition-all transform active:scale-95 text-sm tracking-wide">
            Ajukan
        </button>
    </div>
</form>

<!-- RIWAYAT PENGAJUAN SAYA (COLLAPSIBLE / RECENT STATUS) -->
<?php if (! empty($riwayat)): ?>
    <div class="mt-12 pt-6 border-t border-emerald-200/60">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800 text-base flex items-center gap-2">
                <svg class="w-5 h-5 text-[#33634B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Riwayat Pengajuan Kenaikan Saya</span>
            </h3>
            <span class="text-xs text-gray-500 font-medium"><?= count($riwayat) ?> Riwayat Ditemukan</span>
        </div>

        <div class="bg-white/80 rounded-2xl overflow-hidden border border-emerald-100 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-600 font-bold bg-emerald-50/50">
                            <th class="py-3 px-4 text-center">No.</th>
                            <th class="py-3 px-4">Tanggal Pengajuan</th>
                            <th class="py-3 px-4">Jenis Kenaikan</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4">Catatan Penilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-600">
                        <?php $no = 1; foreach ($riwayat as $r): ?>
                            <tr class="hover:bg-emerald-50/40 transition">
                                <td class="py-3 px-4 text-center font-medium"><?= $no++ ?></td>
                                <td class="py-3 px-4 font-medium"><?= $r->created_at ? esc((string)$r->created_at) : '-' ?></td>
                                <td class="py-3 px-4 font-bold text-gray-800"><?= esc($r->jenis_kenaikan) ?></td>
                                <td class="py-3 px-4 text-center">
                                    <?php if ($r->status === 'Disetujui'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            Disetujui
                                        </span>
                                    <?php elseif ($r->status === 'Ditolak'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                            Ditolak
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                            Menunggu
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-gray-500 italic"><?= esc($r->catatan ?? 'Belum ada catatan') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    // Menampilkan nama file PDF yang dipilih pada elemen label
    function updateFileName(input, labelId) {
        const label = document.getElementById(labelId);
        const container = input.closest('div');
        const badge = container.querySelector('.upload-badge');

        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            label.innerText = fileName;
            label.classList.add('text-emerald-700', 'font-bold');
            if (badge) {
                badge.innerText = '✓ PDF Terpilih';
                badge.classList.remove('bg-gray-100', 'text-gray-600');
                badge.classList.add('bg-emerald-100', 'text-emerald-800');
            }
        }
    }

    // Reset nama label saat form direset
    function resetFormLabels() {
        const defaultLabels = {
            'label_surat_rekomendasi': 'Surat Rekomendasi',
            'label_daftar_riwayat_hidup': 'Daftar Riwayat Hidup',
            'label_surat_pernyataan_disiplin': 'Surat Pernyataan Tidak Sedang Dalam Hukuman Disiplin',
            'label_kartu_keluarga': 'Kartu Keluarga',
            'label_scan_skp': 'Scan SKP',
            'label_sk_terakhir': 'Fotokopi SK Terakhir',
            'label_ijazah_transkrip': 'Fotokopi Ijazah & Transkrip Nilai',
            'label_surat_keterangan_sehat': 'Surat Keterangan Sehat',
            'label_bukti_pelatihan': 'Bukti Pelatihan',
            'label_formulir_usulan': 'Formulir Usulan Kenaikan Pangkat'
        };

        for (const [id, text] of Object.entries(defaultLabels)) {
            const el = document.getElementById(id);
            if (el) {
                el.innerText = text;
                el.classList.remove('text-emerald-700', 'font-bold');
            }
        }

        document.querySelectorAll('.upload-badge').forEach(b => {
            b.innerText = 'Pilih PDF';
            b.classList.remove('bg-emerald-100', 'text-emerald-800');
            b.classList.add('bg-gray-100', 'text-gray-600');
        });
    }
</script>

<?= $this->endSection() ?>
