<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In — FundiPopote</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght=300;400;500;600;700&display=swap');
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

  <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-amber-400 rounded-full opacity-60 filter blur-xl pointer-events-none"></div>
  <div class="absolute -top-16 -right-16 w-64 h-64 bg-rose-500 transform rotate-45 opacity-70 pointer-events-none"></div>

  <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[550px] z-10">
    
    <div class="w-full md:w-2/5 bg-gradient-to-br from-emerald-500 to-teal-600 text-white p-8 md:p-12 flex flex-col justify-between items-center text-center relative">
      <div class="w-full flex items-center justify-start gap-2 mb-8 md:mb-0">
        <div class="border-2 border-white/80 p-1.5 rounded-lg bg-white/10">
          <i class="fa-solid fa-wrench text-sm"></i>
        </div>
        <span class="font-bold tracking-wide text-base">Fundi<span class="text-amber-300">Popote</span></span>
      </div>

      <div class="my-auto space-y-4 max-w-xs">
        <h2 class="text-3xl md:text-4xl font-bold tracking-wide">Hello Friend!</h2>
        <p class="text-sm font-light leading-relaxed opacity-90">
          Enter your personal details and start your journey with us today.
        </p>
        <div class="pt-4">
          <a href="{{ route('register') }}" class="inline-block px-10 py-2.5 border-2 border-white rounded-full font-medium tracking-wide text-sm uppercase transition-all duration-300 hover:bg-white hover:text-emerald-600 focus:outline-none">
            Sign Up
          </a>
        </div>
      </div>
      
      <div class="absolute bottom-10 right-10 w-8 h-8 border border-white/10 transform rotate-45 pointer-events-none"></div>
    </div>

    <div class="w-full md:w-3/5 p-8 md:p-12 flex flex-col justify-center items-center bg-slate-50/50">
      <div class="w-full max-w-sm text-center">
        
        <h1 class="text-3xl font-bold text-emerald-600 tracking-wide mb-2">Sign In</h1>
        <p class="text-gray-400 text-xs tracking-wide mb-6">Tanzania Service Marketplace</p>
        
        <div class="flex justify-center gap-3 mb-6">
          <a href="#" class="w-9 h-9 border border-gray-200 rounded-full flex items-center justify-center text-gray-500 transition-colors hover:bg-emerald-600 hover:text-white hover:border-emerald-600">
            <i class="fa-brands fa-facebook-f text-sm"></i>
          </a>
          <a href="#" class="w-9 h-9 border border-gray-200 rounded-full flex items-center justify-center text-gray-500 transition-colors hover:bg-emerald-600 hover:text-white hover:border-emerald-600">
            <i class="fa-brands fa-google text-sm"></i>
          </a>
          <a href="#" class="w-9 h-9 border border-gray-200 rounded-full flex items-center justify-center text-gray-500 transition-colors hover:bg-emerald-600 hover:text-white hover:border-emerald-600">
            <i class="fa-brands fa-linkedin-in text-sm"></i>
          </a>
        </div>

        <p class="text-xs text-gray-400 uppercase tracking-wider mb-6">or use your email account:</p>

        @if ($errors->any())
          <div class="bg-red-50 border border-red-100 text-red-700 text-left text-sm rounded-xl p-3 mb-4 flex items-start gap-2">
            <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-500"></i>
            <span>{{ $errors->first() }}</span>
          </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4 text-left">
          @csrf
          
          <div class="relative flex items-center">
            <i class="fa-regular fa-envelope absolute left-4 text-gray-400 pointer-events-none text-sm"></i>
            <input 
              type="email" 
              name="email"
              value="{{ old('email') }}" 
              required
              placeholder="email@example.com"
              class="w-full pl-11 pr-4 py-3 bg-gray-100 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/30 transition-all border border-transparent focus:border-emerald-500"
            />
          </div>

          <div class="relative flex items-center">
            <i class="fa-solid fa-lock absolute left-4 text-gray-400 pointer-events-none text-sm"></i>
            <input 
              type="password" 
              name="password" 
              id="password-field"
              required
              placeholder="••••••••"
              class="w-full pl-11 pr-12 py-3 bg-gray-100 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/30 transition-all border border-transparent focus:border-emerald-500"
            />
            <button 
              type="button" 
              onclick="togglePassword()" 
              class="absolute right-4 text-gray-400 hover:text-emerald-600 focus:outline-none"
            >
              <i id="toggle-icon" class="fa-regular fa-eye text-sm"></i>
            </button>
          </div>

          <div class="flex justify-end pt-1">
            <a href="{{ route('password.request') }}" class="text-xs font-medium text-slate-400 hover:text-emerald-600 transition-colors">
              Forgot your password?
            </a>
          </div>

          <div class="pt-2 flex justify-center">
            <button 
              type="submit" 
              class="px-16 py-3 bg-emerald-600 text-white font-medium text-sm tracking-wider uppercase rounded-full shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/30 active:scale-95"
            >
              Sign In
            </button>
          </div>
        </form>

        <p class="text-center text-xs text-gray-400 mt-6">
          Don't have an account?
          <a href="{{ route('register') }}" class="text-emerald-600 hover:underline font-medium ml-1">
              Register here
          </a>
        </p>

      </div>
    </div>

  </div>

  <script>
    function togglePassword() {
      const passwordField = document.getElementById('password-field');
      const toggleIcon = document.getElementById('toggle-icon');
      
      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-regular', 'fa-eye');
        toggleIcon.classList.add('fa-solid', 'fa-eye-slash');
      } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-solid', 'fa-eye-slash');
        toggleIcon.classList.add('fa-regular', 'fa-eye');
      }
    }
  </script>

</body>
</html>