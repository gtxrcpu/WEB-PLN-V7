<x-layouts.app :title="'Tambah User — Admin'">
  <div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white hover:bg-slate-50 text-slate-700 transition-colors shadow-sm border border-slate-200 mb-4">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
      <span class="text-sm font-medium">Kembali</span>
    </a>
    <h1 class="text-2xl font-bold text-gray-900">Tambah User Baru</h1>
    <p class="text-sm text-gray-600 mt-1">Buat akun user baru dengan role</p>
  </div>

  <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 p-6">
    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
      @csrf

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name') }}" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name') border-red-500 @enderror">
        @error('name')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
        <input type="text" name="username" value="{{ old('username') }}" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('username') border-red-500 @enderror">
        @error('username')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('email') border-red-500 @enderror">
        @error('email')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
        <input type="password" name="password" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('password') border-red-500 @enderror">
        @error('password')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
        <select name="role" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('role') border-red-500 @enderror">
          <option value="">Pilih Role</option>
          @foreach($roles as $role)
            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
              {{ ucfirst($role->name) }}
            </option>
          @endforeach
        </select>
        @error('role')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Unit/Cabang</label>
          <select name="unit_id"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('unit_id') border-red-500 @enderror">
            <option value="">Pilih Unit (Opsional)</option>
            @foreach($units as $unit)
              <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                {{ $unit->code }} - {{ $unit->name }}
              </option>
            @endforeach
          </select>
          @error('unit_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Posisi</label>
          <select name="position"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('position') border-red-500 @enderror">
            <option value="">Pilih Posisi (Opsional)</option>
            <option value="leader" {{ old('position') == 'leader' ? 'selected' : '' }}>Leader</option>
            <option value="petugas" {{ old('position') == 'petugas' ? 'selected' : '' }}>Petugas</option>
          </select>
          @error('position')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div class="flex items-center gap-3 pt-4">
        <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors shadow-md font-semibold">
          Simpan User
        </button>
        <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
          Batal
        </a>
      </div>
    </form>
  </div>
</x-layouts.app>
