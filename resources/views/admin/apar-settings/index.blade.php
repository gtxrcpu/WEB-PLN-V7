<x-layouts.app :title="'Settings APAR'">
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Settings APAR</h1>
    <p class="text-gray-600 mt-1">Konfigurasi format kode serial APAR</p>
  </div>

  @if(session('success'))
  <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
    {{ session('success') }}
  </div>
  @endif

  <div class="bg-white rounded-xl shadow-lg p-6">
    <form action="{{ route('admin.apar-settings.update') }}" method="POST">
      @csrf
      @method('PUT')

      {{-- Format Kode --}}
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Format Kode APAR
        </label>
        <input 
          type="text" 
          name="kode_format" 
          value="{{ $settings['kode_format']->value ?? 'APAR-{UNIT}-{YYYY}-{NNNN}' }}"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          required
        >
        <p class="text-xs text-gray-500 mt-2">
          Variabel yang tersedia:
        </p>
        <ul class="text-xs text-gray-600 mt-1 ml-4 list-disc">
          <li><code class="bg-gray-100 px-1 py-0.5 rounded">{UNIT}</code> - Kode unit (UPW2, UPW3)</li>
          <li><code class="bg-gray-100 px-1 py-0.5 rounded">{YYYY}</code> - Tahun 4 digit (2025)</li>
          <li><code class="bg-gray-100 px-1 py-0.5 rounded">{YY}</code> - Tahun 2 digit (25)</li>
          <li><code class="bg-gray-100 px-1 py-0.5 rounded">{MM}</code> - Bulan 2 digit (01-12)</li>
          <li><code class="bg-gray-100 px-1 py-0.5 rounded">{NNNN}</code> - Nomor urut 4 digit (0001, 0002, ...)</li>
          <li><code class="bg-gray-100 px-1 py-0.5 rounded">{NNN}</code> - Nomor urut 3 digit (001, 002, ...)</li>
        </ul>
        <p class="text-xs text-gray-500 mt-2">
          Contoh: <code class="bg-blue-50 text-blue-700 px-2 py-1 rounded">APAR-UPW2-2025-0001</code>
        </p>
        @error('kode_format')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Counter --}}
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Counter Nomor Urut
        </label>
        <div class="flex gap-4">
          <input 
            type="number" 
            name="kode_counter" 
            value="{{ $settings['kode_counter']->value ?? 1 }}"
            min="1"
            class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            required
          >
          <button 
            type="button"
            onclick="if(confirm('Reset counter ke 1?')) document.getElementById('resetForm').submit()"
            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
            Reset Counter
          </button>
        </div>
        <p class="text-xs text-gray-500 mt-2">
          Nomor urut berikutnya yang akan digunakan
        </p>
        @error('kode_counter')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Preview --}}
      <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <p class="text-sm font-medium text-blue-900 mb-2">Preview Kode:</p>
        <p class="text-lg font-mono font-bold text-blue-700" id="preview">
          Loading...
        </p>
      </div>

      {{-- Actions --}}
      <div class="flex gap-4">
        <button 
          type="submit"
          class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
          Simpan Settings
        </button>
        <a 
          href="{{ route('admin.dashboard') }}"
          class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition">
          Kembali
        </a>
      </div>
    </form>
  </div>

  {{-- Reset Form (hidden) --}}
  <form id="resetForm" action="{{ route('admin.apar-settings.reset-counter') }}" method="POST" class="hidden">
    @csrf
  </form>

  <script>
    // Preview kode
    function updatePreview() {
      const format = document.querySelector('input[name="kode_format"]').value;
      const counter = document.querySelector('input[name="kode_counter"]').value;
      
      const now = new Date();
      const yyyy = now.getFullYear();
      const yy = String(yyyy).slice(-2);
      const mm = String(now.getMonth() + 1).padStart(2, '0');
      const nnnn = String(counter).padStart(4, '0');
      const nnn = String(counter).padStart(3, '0');
      
      let preview = format
        .replace('{UNIT}', 'UPW2')
        .replace('{YYYY}', yyyy)
        .replace('{YY}', yy)
        .replace('{MM}', mm)
        .replace('{NNNN}', nnnn)
        .replace('{NNN}', nnn);
      
      document.getElementById('preview').textContent = preview;
    }
    
    // Update preview on input
    document.querySelector('input[name="kode_format"]').addEventListener('input', updatePreview);
    document.querySelector('input[name="kode_counter"]').addEventListener('input', updatePreview);
    
    // Initial preview
    updatePreview();
  </script>
</x-layouts.app>
