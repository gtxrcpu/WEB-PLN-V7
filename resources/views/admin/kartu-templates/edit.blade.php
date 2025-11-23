<x-layouts.app :title="'Edit Template - ' . $moduleName">
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
    <h1 class="text-2xl font-bold text-gray-900">Edit Template: {{ $moduleName }}</h1>
    <p class="text-sm text-gray-600 mt-1">Edit template kartu kendali</p>
  </div>

  <div x-data="{
    headerFields: @js($template->header_fields ?? []),
    inspectionFields: @js(array_map(function($field) {
        // Backward compatibility: tambahkan section jika belum ada
        if (!isset($field['section'])) {
            $field['section'] = 'A';
            $field['section_title'] = '';
        }
        return $field;
    }, $template->inspection_fields ?? [])),
    footerFields: @js($template->footer_fields ?? []),
    saving: false
  }">
  <form action="{{ route('admin.kartu-templates.update', $template->module) }}" method="POST" @submit="saving = true">
    @csrf
    @method('PUT')

    <div class="space-y-6">
      {{-- Basic Info --}}
      <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-bold mb-4 text-indigo-600">Informasi Dasar</h2>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold mb-2">Title</label>
            <input type="text" name="title" value="{{ old('title', $template->title) }}" required
              class="w-full px-4 py-2 border rounded-lg">
          </div>
          <div>
            <label class="block text-sm font-semibold mb-2">Subtitle</label>
            <input type="text" name="subtitle" value="{{ old('subtitle', $template->subtitle) }}" required
              class="w-full px-4 py-2 border rounded-lg">
          </div>
        </div>
      </div>

      {{-- Company Info --}}
      <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-bold mb-4 text-blue-600">Informasi Perusahaan</h2>
        <div class="grid grid-cols-2 gap-4">
          <div class="col-span-2">
            <label class="block text-sm font-semibold mb-2">Nama Perusahaan</label>
            <input type="text" name="company_name" value="{{ old('company_name', $template->company_name) }}"
              class="w-full px-4 py-2 border rounded-lg">
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-semibold mb-2">Alamat</label>
            <textarea name="company_address" rows="2" class="w-full px-4 py-2 border rounded-lg">{{ old('company_address', $template->company_address) }}</textarea>
          </div>
          <div>
            <label class="block text-sm font-semibold mb-2">Telepon</label>
            <input type="text" name="company_phone" value="{{ old('company_phone', $template->company_phone) }}"
              class="w-full px-4 py-2 border rounded-lg">
          </div>
          <div>
            <label class="block text-sm font-semibold mb-2">Fax</label>
            <input type="text" name="company_fax" value="{{ old('company_fax', $template->company_fax) }}"
              class="w-full px-4 py-2 border rounded-lg">
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-semibold mb-2">Email</label>
            <input type="email" name="company_email" value="{{ old('company_email', $template->company_email) }}"
              class="w-full px-4 py-2 border rounded-lg">
          </div>
        </div>
      </div>

      {{-- Header Fields --}}
      <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-bold text-purple-600">Header Fields</h2>
          <button type="button" @click="headerFields.push({label: '', value: ''})"
                  class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
            + Tambah
          </button>
        </div>
        <div class="space-y-2">
          <template x-for="(field, index) in headerFields" :key="index">
            <div class="flex gap-2">
              <input type="text" :name="'header_fields[' + index + '][label]'" x-model="field.label" placeholder="Label" required
                     class="flex-1 px-3 py-2 border rounded-lg">
              <input type="text" :name="'header_fields[' + index + '][value]'" x-model="field.value" placeholder="Value" required
                     class="flex-1 px-3 py-2 border rounded-lg">
              <button type="button" @click="headerFields.splice(index, 1)" 
                      class="px-3 py-2 bg-red-100 text-red-700 rounded-lg">Hapus</button>
            </div>
          </template>
        </div>
      </div>

      {{-- Inspection Fields --}}
      <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="text-lg font-bold text-green-600">Inspection Fields</h2>
            <p class="text-xs text-gray-500 mt-1">
              Field pemeriksaan yang akan muncul di kartu kendali
              @if($template->module === 'rumah-pompa')
                (dikelompokkan per section A, B, C)
              @endif
            </p>
          </div>
          <button type="button" 
                  @click="inspectionFields.push({
                    {{ $template->module === 'rumah-pompa' ? "section: 'A', section_title: ''," : "" }}
                    label: '', type: 'checkbox'
                  })"
                  class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Field
          </button>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b-2 border-gray-200">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-12">#</th>
                @if($template->module === 'rumah-pompa')
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-20">Section</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-48">Judul Section</th>
                @endif
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Label Pemeriksaan</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-40">Tipe Input</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-24">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" x-data="{ isRumahPompa: {{ $template->module === 'rumah-pompa' ? 'true' : 'false' }} }">
              <template x-for="(field, index) in inspectionFields" :key="index">
                <tr class="hover:bg-gray-50 transition-colors">
                  <td class="px-4 py-3 text-sm text-gray-500" x-text="index + 1"></td>
                  
                  <td class="px-4 py-3" x-show="isRumahPompa">
                    <select :name="'inspection_fields[' + index + '][section]'" 
                            x-model="field.section" 
                            :required="isRumahPompa"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-bold focus:ring-2 focus:ring-green-500 focus:border-green-500">
                      <option value="A">A</option>
                      <option value="B">B</option>
                      <option value="C">C</option>
                      <option value="D">D</option>
                      <option value="E">E</option>
                    </select>
                  </td>
                  <td class="px-4 py-3" x-show="isRumahPompa">
                    <input type="text" 
                           :name="'inspection_fields[' + index + '][section_title]'" 
                           x-model="field.section_title" 
                           placeholder="Contoh: PEMIPAAN DAN VALVE" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika item dalam section yang sama</p>
                  </td>
                  
                  <td class="px-4 py-3">
                    <input type="text" 
                           :name="'inspection_fields[' + index + '][label]'" 
                           x-model="field.label" 
                           placeholder="Contoh: Kondisi Tabung" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                  </td>
                  <td class="px-4 py-3">
                    <select :name="'inspection_fields[' + index + '][type]'" 
                            x-model="field.type" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                      <option value="checkbox">✓ Checkbox</option>
                      <option value="text">📝 Text</option>
                      <option value="textarea">📄 Textarea</option>
                    </select>
                  </td>
                  <td class="px-4 py-3 text-center">
                    <button type="button" 
                            @click="inspectionFields.splice(index, 1)" 
                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                      Hapus
                    </button>
                  </td>
                </tr>
              </template>
              <tr x-show="inspectionFields.length === 0">
                <td :colspan="isRumahPompa ? 6 : 4" class="px-4 py-8 text-center text-gray-500">
                  <div class="flex flex-col items-center gap-2">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm font-medium">Belum ada inspection field</p>
                    <p class="text-xs">Klik tombol "Tambah Field" untuk menambahkan</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        @if($template->module === 'rumah-pompa')
        {{-- Info Box - Hanya untuk Rumah Pompa --}}
        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
          <div class="flex gap-3">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-blue-800">
              <p class="font-semibold mb-1">Cara Menggunakan Section:</p>
              <ul class="list-disc list-inside space-y-1 text-xs">
                <li><strong>Section:</strong> Pilih A, B, C, D, atau E untuk mengelompokkan field</li>
                <li><strong>Judul Section:</strong> Isi hanya pada item PERTAMA di setiap section (contoh: "PEMIPAAN DAN VALVE")</li>
                <li><strong>Item berikutnya:</strong> Kosongkan judul section jika masih dalam section yang sama</li>
                <li>Field akan otomatis dikelompokkan dan ditampilkan dengan header section di kartu kendali</li>
              </ul>
            </div>
          </div>
        </div>
        @endif
      </div>

      {{-- Footer Fields --}}
      <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-bold text-orange-600">Footer Fields</h2>
          <button type="button" @click="footerFields.push({label: '', value: ''})"
                  class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
            + Tambah
          </button>
        </div>
        <div class="grid grid-cols-3 gap-3">
          <template x-for="(field, index) in footerFields" :key="index">
            <div class="p-3 bg-orange-50 rounded-lg">
              <input type="text" :name="'footer_fields[' + index + '][label]'" x-model="field.label" placeholder="Label" required
                     class="w-full px-3 py-2 border rounded-lg mb-2">
              <input type="text" :name="'footer_fields[' + index + '][value]'" x-model="field.value" placeholder="Value" required
                     class="w-full px-3 py-2 border rounded-lg mb-2">
              <button type="button" @click="footerFields.splice(index, 1)" 
                      class="w-full px-2 py-1 bg-red-100 text-red-700 rounded">Hapus</button>
            </div>
          </template>
        </div>
      </div>

      {{-- Table Header (Khusus Rumah Pompa) --}}
      @if($template->module === 'rumah-pompa')
      <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-bold mb-4 text-teal-600">Table Header (Khusus Rumah Pompa)</h2>
        <div>
          <label class="block text-sm font-semibold mb-2">Header Kolom Kondisi</label>
          <input type="text" name="table_header" value="{{ old('table_header', $template->table_header ?? 'KONDISI OKTOBER MINGGU 2') }}"
            placeholder="Contoh: KONDISI OKTOBER MINGGU 2"
            class="w-full px-4 py-2 border rounded-lg">
          <p class="text-xs text-gray-500 mt-1">Header ini akan muncul di kolom kanan tabel pemeriksaan</p>
        </div>
      </div>
      @endif

      {{-- Submit --}}
      <div class="flex gap-3">
        <button type="submit" 
                :disabled="saving"
                :class="saving ? 'opacity-75 cursor-not-allowed' : ''"
                class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold inline-flex items-center gap-2">
          <svg x-show="saving" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span x-text="saving ? 'Menyimpan...' : 'Simpan Template'"></span>
        </button>
        <a href="{{ route('admin.kartu-templates.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold">
          Batal
        </a>
      </div>
    </div>
  </form>
  </div>
</x-layouts.app>
