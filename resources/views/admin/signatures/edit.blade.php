<x-layouts.app :title="'Edit TTD — Admin'">
  <div class="mb-6">
    <a href="{{ route('admin.signatures.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors mb-4">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>
      Kembali
    </a>
    <h1 class="text-2xl font-bold text-gray-900">Edit Tanda Tangan</h1>
    <p class="text-sm text-gray-600 mt-1">Update informasi tanda tangan</p>
  </div>

  <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 p-6">
    <form action="{{ route('admin.signatures.update', $signature) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pimpinan *</label>
        <input type="text" name="name" value="{{ old('name', $signature->name) }}" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name') border-red-500 @enderror">
        @error('name')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Jabatan *</label>
        <input type="text" name="position" value="{{ old('position', $signature->position) }}" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('position') border-red-500 @enderror">
        @error('position')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">NIP (Opsional)</label>
        <input type="text" name="nip" value="{{ old('nip', $signature->nip) }}"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('nip') border-red-500 @enderror">
        @error('nip')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanda Tangan Saat Ini</label>
        <div class="bg-slate-50 rounded-lg p-4 flex items-center justify-center min-h-[120px] mb-3">
          @if($signature->signature_url)
            <img src="{{ $signature->signature_url }}" 
                 alt="TTD {{ $signature->name }}" 
                 class="max-h-24 w-auto object-contain">
          @else
            <p class="text-gray-400 text-sm">No signature</p>
          @endif
        </div>

        <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Tanda Tangan Baru (Opsional)</label>
        <input type="file" name="signature" accept="image/png,image/jpeg,image/jpg"
          onchange="previewSignature(event)"
          class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 @error('signature') border-red-500 @enderror">
        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah. Format: PNG, JPG, JPEG. Max: 2MB.</p>
        @error('signature')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div id="signaturePreview" class="mt-4 hidden">
          <p class="text-sm font-medium text-gray-700 mb-2">Preview Baru:</p>
          <div class="bg-slate-50 rounded-lg p-4 flex items-center justify-center min-h-[120px]">
            <img id="previewImage" src="" alt="Preview" class="max-h-24 w-auto object-contain">
          </div>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <input type="checkbox" name="is_active" id="is_active" {{ old('is_active', $signature->is_active) ? 'checked' : '' }}
          class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
        <label for="is_active" class="text-sm font-medium text-gray-700">Aktifkan tanda tangan ini</label>
      </div>

      <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
        <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors shadow-md font-semibold">
          Update TTD
        </button>
        <a href="{{ route('admin.signatures.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
          Batal
        </a>
      </div>
    </form>
  </div>

  <script>
    function previewSignature(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('previewImage').src = e.target.result;
          document.getElementById('signaturePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
      }
    }
  </script>
</x-layouts.app>
