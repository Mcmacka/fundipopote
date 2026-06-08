<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password — FundiPopote</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght=300;400;500;600;700&display=swap');
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

  <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-amber-400 rounded-full opacity-60 filter blur-xl pointer-events-none"></div>
  <div class="absolute -top-16 -right-16 w-64 h-64 bg-rose-500 transform rotate-45 opacity-70 pointer-events-none"></div>

  <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 md:p-10 z-10">
    <div class="w-full text-center">
      
      <h1 class="text-2xl font-bold text-emerald-600 tracking-wide mb-2">Forgot Password?</h1>
      <p class="text-gray-500 text-xs mb-6">Enter your email address and we will send you a secure link to reset your password.</p>

      @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 text-left text-sm rounded-xl p-3 mb-4">
          {{ session('status') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="bg-red-50 border border-red-100 text-red-700 text-left text-sm rounded-xl p-3 mb-4 flex items-start gap-2">
          <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-500"></i>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}" class="space-y-4 text-left">
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

        <div class="pt-2 flex flex-col gap-4 items-center">
          <button 
            type="submit" 
            class="w-full py-3 bg-emerald-600 text-white font-medium text-sm tracking-wider uppercase rounded-full shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/30 active:scale-95"
          >
            Send Reset Link
          </button>
          
          <a href="{{ route('login') }}" class="text-xs text-gray-400 hover:text-emerald-600 font-medium transition-colors">
            Back to Sign In
          </a>
        </div>
      </form>

    </div>
  </div>

</body>
</html>