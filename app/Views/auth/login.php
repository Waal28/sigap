<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIGAP Dinas Lingkungan Hidup Kabupaten Bengkalis</title>
    <meta name="description" content="Halaman Masuk SIGAP - Sistem Kenaikan Gaji dan Pangkat Dinas Lingkungan Hidup Kabupaten Bengkalis">

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4 sm:p-6 antialiased">

    <!-- MAIN LOGIN CARD -->
    <div class="w-full max-w-[420px] rounded-[32px] overflow-hidden shadow-2xl border border-gray-100 flex flex-col transition-all duration-300">
        
        <!-- TOP SECTION (MINT GREEN) -->
        <div class="bg-[#E9F5EF] pt-8 pb-7 px-8 text-center flex flex-col items-center">
            
            <!-- SIGAP BADGE CONTAINER -->
            <div class="bg-[#232726] rounded-2xl px-5 py-3 flex items-center gap-3.5 shadow-md">
                <!-- Star Ribbon Icon -->
                <div class="w-11 h-11 rounded-xl bg-[#2ECC71] flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-7 h-7 text-[#F1C40F] drop-shadow" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l2.4 5.3 5.8.5-4.4 3.9 1.3 5.8-5.1-3.1-5.1 3.1 1.3-5.8-4.4-3.9 5.8-.5L12 2z"/>
                        <path d="M9 17.5v4.5l3-2 3 2v-4.5" fill="#F1C40F"/>
                    </svg>
                </div>
                <div class="text-left leading-tight">
                    <h1 class="text-white font-extrabold text-xl tracking-wider leading-none">SIGAP</h1>
                    <p class="text-gray-300 text-[10px] font-normal tracking-tight mt-0.5">Sistem Kenaikan Gaji dan Pangkat</p>
                </div>
            </div>

            <!-- TITLE & SUBTITLE -->
            <div class="mt-6 text-gray-900 leading-snug">
                <p class="font-bold text-sm tracking-tight">Sistem Kenaikan Gaji dan Pangkat</p>
                <p class="font-bold text-sm tracking-tight">Dinas Lingkungan Hidup Kabupaten Bengkalis</p>
            </div>
        </div>

        <!-- BOTTOM SECTION (FOREST GREEN) -->
        <div class="bg-[#33634B] px-8 pt-7 pb-9">
            
            <!-- NOTIFICATIONS / ALERTS -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 bg-rose-500/20 border border-rose-300 text-rose-100 text-xs px-4 py-2.5 rounded-full flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-rose-200" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span><?= esc(session()->getFlashdata('error')) ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-4 bg-emerald-500/20 border border-emerald-300 text-emerald-100 text-xs px-4 py-2.5 rounded-full flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-emerald-200" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span><?= esc(session()->getFlashdata('success')) ?></span>
                </div>
            <?php endif; ?>

            <!-- LOGIN FORM -->
            <form action="<?= base_url('login') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <!-- INPUT NIP -->
                <div class="relative flex items-center bg-white rounded-full px-4 py-2.5 shadow-md focus-within:ring-2 focus-within:ring-emerald-300 transition">
                    <div class="text-gray-700 mr-3 shrink-0">
                        <!-- User Silhouette Icon -->
                        <svg class="w-5 h-5 text-gray-800" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <input type="text" 
                           id="nip"
                           name="nip" 
                           value="<?= old('nip') ?>"
                           required
                           autocomplete="username"
                           placeholder="Nomor Induk Pegawai" 
                           class="w-full bg-transparent text-gray-800 text-sm font-medium placeholder-gray-500 focus:outline-none">
                </div>

                <!-- INPUT KATA SANDI -->
                <div class="relative flex items-center bg-white rounded-full px-4 py-2.5 shadow-md focus-within:ring-2 focus-within:ring-emerald-300 transition">
                    <div class="text-gray-700 mr-3 shrink-0">
                        <!-- Lock Icon -->
                        <svg class="w-5 h-5 text-gray-800" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <input type="password" 
                           id="passwordInput"
                           name="password" 
                           required
                           autocomplete="current-password"
                           placeholder="Kata Sandi" 
                           class="w-full bg-transparent text-gray-800 text-sm font-medium placeholder-gray-500 focus:outline-none pr-2">
                    <!-- Toggle Show/Hide Password -->
                    <button type="button" 
                            id="togglePasswordBtn"
                            onclick="togglePassword()"
                            class="text-gray-700 hover:text-black focus:outline-none shrink-0 transition"
                            title="Tampilkan / Sembunyikan Kata Sandi">
                        <!-- Eye Icon -->
                        <svg id="eyeIcon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        <!-- Eye Slash Icon (Hidden by default) -->
                        <svg id="eyeSlashIcon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                            <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                        </svg>
                    </button>
                </div>

                <!-- SUBMIT BUTTON -->
                <div class="pt-4 flex justify-center">
                    <button type="submit" 
                            class="bg-white hover:bg-gray-100 text-gray-900 font-bold px-12 py-2.5 rounded-full shadow-md hover:shadow-lg transition-all transform active:scale-95 text-sm tracking-wide">
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script to toggle password visibility -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeSlashIcon = document.getElementById('eyeSlashIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
