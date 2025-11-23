<x-layouts.app :title="'Buat Template Baru'">
  <div class="mb-4">
    <a href="{{ route('admin.kartu-templates.index') }}" 
            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white hover:bg-slate-50 text-slate-700 transition-colors shadow-sm border border-slate-200">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
      <span class="text-sm font-medium">Kembali</span>
    </a>
  </div>

  <div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Buat Template Baru</h1>
    <p class="text-sm text-gray-600 mt-1">Pilih modul untuk membuat template</p>
  </div>

  <form action="{{ route('admin.kartu-templates.store') }}" method="POST">
    @csrf

    <div class="bg-white rounded-xl shadow-lg p-6 max-w-2xl">
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-semibold mb-2">Pilih Modul</label>
          <select name="module" required class="w-full px-4 py-2 border rounded-lg">
            <option value="">-- Pilih Modul --</option>
            @foreach($availableModules as $key => $name)
              <option value="{{ $key }}">{{ $name }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-semibold mb-2">Title</label>
          <input type="text" name="title" required placeholder="KARTU KENDALI"
            class="w-full px-4 py-2 border rounded-lg">
        </div>

        <div>
          <label class="block text-sm font-semibold mb-2">Subtitle</label>
          <input type="text" name="subtitle" required placeholder="ALAT PEMADAM API RINGAN (APAR)"
            class="w-full px-4 py-2 border rounded-lg">
        </div>
      </div>

      <div class="mt-6 flex gap-3">
        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold">
          Buat Template
        </button>
        <a href="{{ route('admin.kartu-templates.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold">
          Batal
        </a>
      </div>
    </div>
  </form>
</x-layouts.app>
