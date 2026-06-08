<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Password — FundiPopote</title>
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
      
      <h1 class="text-2xl font-bold text-emerald-600 tracking-wide mb-2">Create New Password</h1>
      <p class="text-gray-500 text-xs mb-6">Please enter your email and choose a strong secure password.</p>

      @if ($errors->any())
        <div class="bg-red-50 border border-red-100 text-red-700 text-left text-sm rounded-xl p-3 mb-4 flex items-start gap-2">
          <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-500"></i>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('password.update') }}" class="space-y-4 text-left">
        @csrf
        
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="relative flex items-center">
          <i class="fa-regular fa-envelope absolute left-4 text-gray-400 pointer-events-none text-sm"></i>
          <input 
            type="email" 
            name="email"
            value="{{ old('email', $email) }}" 
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
            required
            placeholder="New Password"
            class="w-full pl-11 pr-4 py-3 bg-gray-100 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/30 transition-all border border-transparent focus:border-emerald-500"
          />
        </div>

        <div class="relative flex items-center">
          <i class="fa-solid fa-lock absolute left-4 text-gray-400 pointer-events-none text-sm"></i>
          <input 
            type="password" 
            name="password_confirmation" 
            required
            placeholder="Confirm Password"
            class="w-full pl-11 pr-4 py-3 bg-gray-100 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/30 transition-all border border-transparent focus:border-emerald-500"
          />
        </div>

        <div class="pt-2">
          <button 
            type="submit" 
            class="w-full py-3 bg-emerald-600 text-white font-medium text-sm tracking-wider uppercase rounded-full shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/30 active:scale-95"
          >
            Reset Password
          </button>
        </div>
      </form>

    </div>
  </div>

</body>
</html>