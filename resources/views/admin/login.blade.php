<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Adelia Catering Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f8fafc; /* Slate 50 */
        }
        .login-card {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(226, 232, 240, 0.8); /* Slate 200 */
            backdrop-filter: blur(12px);
        }
        .input-field {
            background: #f1f5f9; /* Slate 100 */
            border: 1px solid #e2e8f0; /* Slate 200 */
            color: #0f172a; /* Slate 900 */
            transition: all 0.2s ease;
        }
        .input-field:focus {
            border-color: #f97316; /* Orange 500 */
            box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.15);
            background: #ffffff;
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4">

    <!-- Subtle Radial Glow -->
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-orange-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-[400px] relative z-10 flex flex-col gap-6">
        
        <!-- Brand Logo & Title -->
        <div class="text-center">
            <div class="w-12 h-12 mx-auto mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="Adelia Catering" class="w-full h-full object-contain filter drop-shadow-[0_2px_8px_rgba(249,115,22,0.2)]">
            </div>
            <h1 class="text-zinc-900 text-lg font-bold tracking-tight">Adelia Catering</h1>
            <p class="text-zinc-500 text-xs mt-1">Admin Control Room</p>
        </div>

        <!-- Login Card -->
        <div class="login-card rounded-2xl p-6 sm:p-8 shadow-2xl shadow-slate-200/50">
            
            <div class="mb-6">
                <h2 class="text-zinc-900 text-base font-bold">Selamat Datang</h2>
                <p class="text-zinc-500 text-xs mt-1">Silakan masuk untuk mengelola pesanan & katering.</p>
            </div>

            <!-- Error Alerts -->
            @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 text-red-600 rounded-xl p-3 flex items-start gap-2.5 text-xs">
                <i class="fa-solid fa-triangle-exclamation mt-0.5 shrink-0"></i>
                <span class="font-medium">{{ $errors->first() }}</span>
            </div>
            @endif

            <!-- Form -->
            <form method="POST" action="/admin/login" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-zinc-600 text-xs font-semibold uppercase tracking-wider mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400 text-xs">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email', 'admin@admin.com') }}" required
                            class="input-field w-full rounded-xl pl-10 pr-4 py-3 outline-none text-xs font-medium"
                            placeholder="admin@email.com">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-zinc-600 text-xs font-semibold uppercase tracking-wider">Password</label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400 text-xs">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" name="password" id="passwordInput" value="password" required
                            class="input-field w-full rounded-xl pl-10 pr-10 py-3 outline-none text-xs font-medium tracking-wide"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-zinc-400 hover:text-zinc-600">
                            <i class="fa-regular fa-eye text-xs" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-zinc-300 bg-white text-orange-500 focus:ring-0 focus:ring-offset-0">
                        <span class="text-zinc-600 text-xs group-hover:text-zinc-800 transition-colors">Ingat sesi saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-bold py-3 rounded-xl transition duration-200 text-xs flex items-center justify-center gap-1.5 shadow-lg shadow-orange-500/10 active:scale-[0.98]">
                        <span>Masuk</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Back to site link -->
        <div class="text-center">
            <a href="/" class="text-zinc-500 hover:text-zinc-800 text-xs transition-colors inline-flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-[9px]"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        <!-- Copyright -->
        <p class="text-center text-zinc-400 text-[10px]">
            &copy; {{ date('Y') }} Adelia Catering Jepara
        </p>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>
