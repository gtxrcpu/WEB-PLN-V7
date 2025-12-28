<x-layouts.app :title="'Profile'">
  <div class="mb-4 sm:mb-6">
    <a href="{{ auth()->user()->hasRole('superadmin') ? route('admin.dashboard') : (auth()->user()->hasRole('leader') ? route('leader.dashboard') : route('user.dashboard')) }}" 
            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white hover:bg-slate-50 text-slate-700 transition-colors shadow-sm border border-slate-200 mb-4">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
      <span class="text-sm font-medium">Kembali ke Dashboard</span>
    </a>

    <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
    <p class="text-sm text-gray-600 mt-1">Kelola informasi profile dan password Anda</p>
  </div>

  @if(session('success'))
    <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-lg flex items-center gap-3 shadow-sm">
      <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <span class="text-green-800 font-medium">{{ session('success') }}</span>
    </div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Profile Information --}}
    <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 p-6">
      <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
        </div>
        <div>
          <h2 class="text-lg font-bold text-gray-900">Informasi Profile</h2>
          <p class="text-sm text-gray-600">Update nama, email, dan username</p>
        </div>
      </div>

      <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- Avatar Upload --}}
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Avatar</label>
          <div class="flex items-center gap-4">
            <div class="relative">
              @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-20 h-20 rounded-full object-cover ring-2 ring-blue-200">
              @else
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-2xl ring-2 ring-blue-200">
                  {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
              @endif
            </div>
            <div class="flex-1">
              <input type="file" name="avatar" accept="image/*" id="avatarInput" onchange="previewAvatar(event)"
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('avatar') border-red-500 @enderror">
              <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF (Max 2MB)</p>
              @error('avatar')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
          </div>
          <div id="avatarPreview" class="hidden mt-2">
            <p class="text-xs text-gray-600 mb-2">Preview:</p>
            <img id="previewImage" src="" alt="Preview" class="w-32 h-32 rounded-lg object-cover ring-2 ring-blue-200">
          </div>
        </div>

        <script>
          function previewAvatar(event) {
            const file = event.target.files[0];
            if (file) {
              const reader = new FileReader();
              reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('avatarPreview').classList.remove('hidden');
              }
              reader.readAsDataURL(file);
            }
          }
        </script>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
          <input type="text" name="name" value="{{ old('name', $user->name) }}" required
            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
          @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
          <input type="email" name="email" value="{{ old('email', $user->email) }}" required
            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
          @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Username <span class="text-gray-400 text-xs">(opsional)</span></label>
          <input type="text" name="username" value="{{ old('username', $user->username) }}"
            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('username') border-red-500 @enderror"
            placeholder="Kosongkan jika tidak ingin diubah">
          @error('username')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="pt-2">
          <button type="submit" class="w-full px-6 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all shadow-md font-semibold">
            Update Profile
          </button>
        </div>
      </form>
    </div>

    {{-- Change Password --}}
    <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 p-6">
      <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center shadow-lg">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </div>
        <div>
          <h2 class="text-lg font-bold text-gray-900">Ubah Password</h2>
          <p class="text-sm text-gray-600">Update password untuk keamanan</p>
        </div>
      </div>

      <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Password Saat Ini</label>
          <input type="password" name="current_password" required
            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('current_password') border-red-500 @enderror">
          @error('current_password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
          <input type="password" name="password" required
            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('password') border-red-500 @enderror">
          @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru</label>
          <input type="password" name="password_confirmation" required
            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
        </div>

        <div class="pt-2">
          <button type="submit" class="w-full px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all shadow-md font-semibold">
            Update Password
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- User Info Card --}}
  <div class="mt-6 bg-gradient-to-r from-slate-50 to-slate-100 rounded-xl p-6 border border-slate-200">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="font-bold text-gray-900 mb-1">Account Information</h3>
        <div class="space-y-1 text-sm text-gray-600">
          <p><span class="font-semibold">Role:</span> 
            <span class="px-2 py-1 text-xs font-bold rounded-full
              @if($user->hasRole('superadmin')) bg-purple-100 text-purple-700
              @elseif($user->hasRole('leader')) bg-green-100 text-green-700
              @else bg-blue-100 text-blue-700 @endif">
              {{ strtoupper($user->getRoleNames()->first() ?? 'user') }}
            </span>
          </p>
          <p><span class="font-semibold">Member Since:</span> {{ $user->created_at->format('d M Y') }}</p>
          <p><span class="font-semibold">Last Updated:</span> {{ $user->updated_at->format('d M Y H:i') }}</p>
        </div>
      </div>
      <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-2xl shadow-lg">
        {{ strtoupper(substr($user->name, 0, 1)) }}
      </div>
    </div>
  </div>
</x-layouts.app>
