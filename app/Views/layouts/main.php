<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard') ?> - SIGAP DLH Bengkalis</title>
    <meta name="description" content="SIGAP - Sistem Kenaikan Gaji dan Pangkat Dinas Lingkungan Hidup Kabupaten Bengkalis">
    
    <!-- Google Fonts: Poppins & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'Inter', 'sans-serif'],
                    },
                    colors: {
                        sigap: {
                            green: '#2D6A4F',
                            darkGreen: '#1B4332',
                            mint: '#E8F5E9',
                            darkBox: '#232726',
                            gold: '#F39C12',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased min-h-screen">

<div class="flex min-h-screen w-full">
    <!-- SIDEBAR (HIJAU GELAP #2D6A4F) -->
    <aside class="w-64 bg-[#2D6A4F] text-white flex flex-col justify-between shrink-0 shadow-lg">
        <div>
            <!-- LOGO HEADER -->
            <div class="p-4 sm:p-5">
                <a href="<?= base_url('dashboard') ?>" class="block">
                    <div class="bg-[#232726] rounded-2xl p-3 flex items-center gap-3 shadow-md hover:bg-[#2b302f] transition">
                        <!-- Star Ribbon Icon -->
                        <div class="w-10 h-10 rounded-xl bg-[#2ECC71] flex items-center justify-center shrink-0 shadow-sm relative overflow-hidden">
                            <svg class="w-6 h-6 text-[#F1C40F] drop-shadow" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2l2.4 5.3 5.8.5-4.4 3.9 1.3 5.8-5.1-3.1-5.1 3.1 1.3-5.8-4.4-3.9 5.8-.5L12 2z"/>
                                <path d="M9 17.5v4.5l3-2 3 2v-4.5" fill="#F1C40F"/>
                            </svg>
                        </div>
                        <div class="leading-tight">
                            <span class="block text-white font-extrabold text-lg tracking-wider">SIGAP</span>
                            <span class="block text-gray-300 text-[9px] font-normal tracking-tight">Sistem Kenaikan Gaji dan Pangkat</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- NAVIGATION MENU -->
            <div class="px-5 mt-3">
                <!-- Section Title: Menu -->
                <div class="flex items-center gap-2.5 text-white font-bold text-base mb-4">
                    <svg class="w-5 h-5 text-white shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span>Menu</span>
                </div>

                <?php 
                    $role = (string) (session()->get('role') ?? 'PEGAWAI');
                    $active = $activeMenu ?? '';
                ?>

                <nav class="space-y-3.5 pl-3">
                    <!-- Dashboard Link -->
                    <a href="<?= base_url('dashboard') ?>" 
                       class="flex items-center gap-3 text-sm font-medium transition py-1.5 px-3 rounded-xl <?= $active === 'dashboard' ? 'bg-[#1E4E38] text-white font-semibold shadow-inner' : 'text-gray-100 hover:text-white hover:bg-[#1E4E38]/60' ?>">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <?php if ($role === 'ADMIN'): ?>
                        <!-- Menu Data Pegawai (ADMIN only) -->
                        <a href="<?= base_url('admin/pegawai') ?>" 
                           class="flex items-center gap-3 text-sm font-medium transition py-1.5 px-3 rounded-xl <?= $active === 'pegawai' ? 'bg-[#1E4E38] text-white font-semibold shadow-inner' : 'text-gray-100 hover:text-white hover:bg-[#1E4E38]/60' ?>">
                            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            <span>Data Pegawai</span>
                        </a>
                    <?php endif; ?>

                    <!-- Menu Pengajuan Kenaikan (All Roles) -->
                    <a href="<?= base_url('pengajuan') ?>" 
                       class="flex items-center gap-3 text-sm font-medium transition py-1.5 px-3 rounded-xl <?= $active === 'pengajuan' ? 'bg-[#1E4E38] text-white font-semibold shadow-inner' : 'text-gray-100 hover:text-white hover:bg-[#1E4E38]/60' ?>">
                        <svg class="w-5 h-5 shrink-0 rotate-45 -mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                        </svg>
                        <span>Pengajuan Kenaikan</span>
                    </a>

                    <!-- Menu Laporan (All Roles) -->
                    <a href="<?= base_url('laporan') ?>" 
                       class="flex items-center gap-3 text-sm font-medium transition py-1.5 px-3 rounded-xl <?= $active === 'laporan' ? 'bg-[#1E4E38] text-white font-semibold shadow-inner' : 'text-gray-100 hover:text-white hover:bg-[#1E4E38]/60' ?>">
                        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                        <span>Laporan</span>
                    </a>

                    <!-- Tombol Keluar (All Roles) -->
                    <div class="pt-2">
                        <button type="button" 
                                onclick="openLogoutModal()"
                                class="w-full flex items-center gap-3 text-sm font-medium transition py-1.5 px-3 rounded-xl text-gray-100 hover:text-white hover:bg-[#1E4E38]/60 text-left">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </div>
                </nav>
            </div>
        </div>

        <!-- USER PROFILE FOOTER -->
        <div class="border-t border-[#1E4E38] px-6 py-4 flex items-center gap-3 bg-[#245841]">
            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white shrink-0">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="truncate">
                <?php 
                    $roleLabel = match($role) {
                        'ADMIN'            => 'Admin',
                        'PEJABAT_PENILAI'  => 'Pejabat Penilai',
                        'PEGAWAI'          => 'Pegawai',
                        default            => esc($role),
                    };
                ?>
                <p class="font-bold text-sm text-white truncate"><?= esc($roleLabel) ?></p>
                <p class="text-[11px] text-gray-200 truncate"><?= esc((string) session()->get('nama')) ?></p>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <main class="flex-1 bg-white p-6 md:p-8 overflow-y-auto">
        <!-- Container card matching design: soft pale green (#E8F5E9) rounded-3xl -->
        <div class="bg-[#E8F5E9] rounded-3xl p-6 md:p-8 min-h-[calc(100vh-4rem)] flex flex-col shadow-sm border border-emerald-100/60">
            <!-- FLASH MESSAGES (TAILWIND POLISHED) -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-6 bg-emerald-50 border border-emerald-300 text-emerald-900 px-5 py-3.5 rounded-2xl flex items-center justify-between shadow-sm text-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-200/80 text-emerald-800 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="font-medium"><?= esc(session()->getFlashdata('success')) ?></span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-bold ml-2">×</button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-6 bg-rose-50 border border-rose-300 text-rose-900 px-5 py-3.5 rounded-2xl flex items-center justify-between shadow-sm text-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-rose-200/80 text-rose-800 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="font-medium"><?= esc(session()->getFlashdata('error')) ?></span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900 font-bold ml-2">×</button>
                </div>
            <?php endif; ?>

            <!-- PAGE CONTENT -->
            <?= $this->renderSection('content') ?>
        </div>
    </main>
</div>

<!-- ========================================== -->
<!-- MODAL DIALOG KONFIRMASI LOGOUT             -->
<!-- ========================================== -->
<div id="logoutModal" 
     class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[28px] max-w-sm w-full p-6 text-center shadow-2xl border border-gray-100 transform transition-all">
        <!-- Icon Keluar (Circle Rose) -->
        <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 shadow-inner">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
        </div>

        <h3 class="text-lg font-bold text-gray-900 mb-1.5">Konfirmasi Keluar</h3>
        <p class="text-xs text-gray-500 mb-6 leading-relaxed">
            Apakah Anda yakin ingin keluar dari sistem <strong class="text-gray-700">SIGAP</strong>? Anda harus login kembali untuk mengakses data pengajuan.
        </p>

        <!-- Tombol Aksi -->
        <div class="flex items-center justify-center gap-3">
            <button type="button" 
                    onclick="closeLogoutModal()"
                    class="flex-1 py-2.5 px-4 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold transition">
                Batal
            </button>
            <a href="<?= base_url('logout') ?>" 
               class="flex-1 py-2.5 px-4 rounded-full bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-md shadow-rose-600/20 text-center">
                Ya, Keluar
            </a>
        </div>
    </div>
</div>

<script>
    function openLogoutModal() {
        const modal = document.getElementById('logoutModal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logoutModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Tutup jika tekan tombol ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeLogoutModal();
        }
    });

    // Tutup jika klik area latar gelap di luar modal
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('logoutModal');
        if (modal && event.target === modal) {
            closeLogoutModal();
        }
    });
</script>

</body>
</html>
