<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register — FundiPopote</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Poppins:wght@400;500;600&display=swap');
    
    .brand-heading { font-family: 'Fredoka', sans-serif; }
    body { font-family: 'Poppins', sans-serif; }
    .hello-text { font-family: 'Georgia', cursive, sans-serif; font-style: italic; }
  </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 md:p-12 relative overflow-y-auto py-8">

  <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-amber-400 rounded-full opacity-60 filter blur-xl pointer-events-none"></div>
  

  <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl flex flex-col md:flex-row min-h-[600px] z-10 overflow-hidden md:overflow-visible">
    
    <div class="w-full md:w-2/5 bg-gradient-to-br from-emerald-500 to-teal-600 text-white p-8 md:p-12 flex flex-col justify-between items-center text-center relative rounded-t-2xl md:rounded-t-none md:rounded-l-2xl">
      <div class="w-full flex items-center justify-start gap-2 mb-8 md:mb-0">
        <div class="border-2 border-white/80 p-1.5 rounded-lg bg-white/10">
          <i class="fa-solid fa-wrench text-sm"></i>
        </div>
        <span class="font-bold tracking-wide text-base">Fundi<span class="text-amber-300">Popote</span></span>
      </div>

      <div class="my-auto space-y-4 max-w-xs py-4 md:py-0">
        <div class="text-white text-5xl font-light tracking-wide hello-text mb-2 select-none">hello</div>
        <h2 class="text-2xl md:text-3xl font-bold tracking-wide brand-heading">Great Service!</h2>
        <p class="text-sm font-light leading-relaxed opacity-90">To stay connected with our community, please register your account details here.</p>
        <div class="pt-4">
          <a href="{{ route('login') }}" class="inline-block px-10 py-2.5 border-2 border-white rounded-full font-medium tracking-wide text-sm uppercase transition-all duration-300 hover:bg-white hover:text-emerald-600">
            Sign In
          </a>
        </div>
      </div>
      <div class="absolute bottom-10 right-10 w-8 h-8 border border-white/10 transform rotate-45 pointer-events-none"></div>
    </div>

    <div class="w-full md:w-3/5 p-8 md:p-10 flex flex-col justify-center items-center bg-slate-50/50 rounded-b-2xl md:rounded-b-none md:rounded-r-2xl">
      <div class="w-full max-w-sm text-center">
        <h1 class="text-3xl font-bold text-emerald-600 tracking-wide mb-1 brand-heading">Register Account</h1>
        <p class="text-gray-400 text-xs tracking-wide mb-6">Tanzania Service Marketplace</p>

        @if ($errors->any())
          <div class="bg-red-50 border border-red-100 text-red-700 text-left text-sm rounded-xl p-3 mb-4">
            <ul class="list-disc list-inside space-y-0.5 text-xs text-red-600 font-medium">
              @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-3.5 text-left">
          @csrf

          <div class="relative flex items-center">
            <i class="fa-regular fa-user absolute left-4 text-gray-400 pointer-events-none text-sm"></i>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Full Name" class="w-full pl-11 pr-4 py-2.5 bg-gray-100 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/30 transition-all border border-transparent focus:border-emerald-500" />
          </div>

          <div class="relative flex items-center">
            <i class="fa-regular fa-envelope absolute left-4 text-gray-400 pointer-events-none text-sm"></i>
            <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email Address" class="w-full pl-11 pr-4 py-2.5 bg-gray-100 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/30 transition-all border border-transparent focus:border-emerald-500" />
          </div>

          <div class="relative flex items-center">
            <i class="fa-solid fa-phone absolute left-4 text-gray-400 pointer-events-none text-sm"></i>
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+255 712 345 678" class="w-full pl-11 pr-4 py-2.5 bg-gray-100 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/30 transition-all border border-transparent focus:border-emerald-500" />
          </div>

          <div class="relative flex items-center">
            <i class="fa-solid fa-users absolute left-4 text-gray-400 pointer-events-none text-sm"></i>
            <select name="role" id="role-select" required class="w-full pl-11 pr-10 py-2.5 bg-gray-100 rounded-xl text-sm text-gray-700 appearance-none focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/30 transition-all border border-transparent focus:border-emerald-500">
              <option value="">-- Select Role --</option>
              <option value="customer" {{ old('role')=='customer' ? 'selected':'' }}>Customer</option>
              <option value="technician" {{ old('role')=='technician' ? 'selected':'' }}>Technician</option>
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400 text-xs"><i class="fa-solid fa-chevron-down"></i></div>
          </div>

          <div id="technician-fields" class="space-y-3.5" style="display: {{ old('role') == 'technician' ? 'block' : 'none' }};">
            <div class="relative flex items-center">
                <i class="fa-solid fa-list absolute left-4 text-gray-400 pointer-events-none text-sm"></i>
                <select name="category_id" class="w-full pl-11 pr-10 py-2.5 bg-gray-100 rounded-xl text-sm text-gray-700 appearance-none focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/30 transition-all border border-transparent focus:border-emerald-500">
                    <option value="">-- Select Service Category --</option>
                    @foreach(\App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400 text-xs"><i class="fa-solid fa-chevron-down"></i></div>
            </div>

            <div class="relative flex flex-col">
              <label class="text-xs text-gray-500 ml-1 mb-1">Professional Certificate</label>
              <input type="file" name="certificate" class="w-full p-2 bg-gray-100 rounded-xl text-sm text-gray-700 border border-transparent focus:bg-white transition-all">
            </div>
            <div class="relative flex flex-col">
              <label class="text-xs text-gray-500 ml-1 mb-1">Residency Letter</label>
              <input type="file" name="residency_letter" class="w-full p-2 bg-gray-100 rounded-xl text-sm text-gray-700 border border-transparent focus:bg-white transition-all">
            </div>
          </div>

          <div class="relative flex items-center">
            <i class="fa-solid fa-lock absolute left-4 text-gray-400 pointer-events-none text-sm"></i>
            <input type="password" name="password" required minlength="8" placeholder="Password" class="w-full pl-11 pr-4 py-2.5 bg-gray-100 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/30 transition-all border border-transparent focus:border-emerald-500" />
          </div>

          <div class="relative flex items-center">
            <i class="fa-solid fa-shield-halved absolute left-4 text-gray-400 pointer-events-none text-sm"></i>
            <input type="password" name="password_confirmation" required placeholder="Confirm Password" class="w-full pl-11 pr-4 py-2.5 bg-gray-100 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/30 transition-all border border-transparent focus:border-emerald-500" />
          </div>

          <div class="pt-2 flex justify-center">
            <button type="submit" class="w-full px-16 py-3 bg-emerald-600 text-white font-medium text-sm tracking-wider uppercase rounded-full shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 transition-all duration-300 active:scale-95">Register</button>
          </div>
        </form>

        <p class="text-center text-xs text-gray-400 mt-5">Already have an account? <a href="{{ route('login') }}" class="text-emerald-600 hover:underline font-medium ml-1">Sign In here</a></p>
      </div>
    </div>
  </div>

  <script>
    const roleSelect = document.getElementById('role-select');
    const techFields = document.getElementById('technician-fields');
    roleSelect.addEventListener('change', function() {
        techFields.style.display = (this.value === 'technician') ? 'block' : 'none';
    });
  </script>
</body>
</html>