<x-layouts.app :title="'Edit Floor Plan — Admin'">
  <div class="mb-6">
    <a href="{{ route('admin.floor-plans.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white hover:bg-slate-50 text-slate-700 transition-colors shadow-sm border border-slate-200 mb-4">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
      <span class="text-sm font-medium">Kembali</span>
    </a>
    <h1 class="text-2xl font-bold text-gray-900">Edit Floor Plan</h1>
    <p class="text-sm text-gray-600 mt-1">Update informasi denah gedung</p>
  </div>

  <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 p-6">
    <form action="{{ route('admin.floor-plans.update', $floorPlan) }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="floorPlanForm()">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Unit/Cabang <span class="text-red-500">*</span></label>
        <select name="unit_id" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('unit_id') border-red-500 @enderror">
          <option value="">Pilih Unit</option>
          @foreach($units as $unit)
            <option value="{{ $unit->id }}" {{ old('unit_id', $floorPlan->unit_id) == $unit->id ? 'selected' : '' }}>
              {{ $unit->code }} - {{ $unit->name }}
            </option>
          @endforeach
        </select>
        @error('unit_id')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Floor Plan <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $floorPlan->name) }}" required
          placeholder="Contoh: Lantai 1 - Gedung Utama"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
        @error('name')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Current Image --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Denah Saat Ini</label>
        <div class="bg-gray-100 rounded-lg p-4">
          <img src="{{ url($floorPlan->image_path) }}" 
               alt="{{ $floorPlan->name }}"
               class="max-h-64 mx-auto rounded-lg shadow-md">
          <p class="text-xs text-gray-500 text-center mt-2">{{ $floorPlan->width }} × {{ $floorPlan->height }} px</p>
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Ganti Gambar Denah (opsional)</label>
        
        <!-- Hidden File Input -->
        <input 
          id="image" 
          name="image" 
          type="file" 
          class="hidden" 
          accept="image/jpeg,image/png,image/jpg,image/svg+xml" 
          @change="previewImage"
          onchange="handleFileSelect(event)"
        >
        
        <!-- Upload Area -->
        <div 
          class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors cursor-pointer"
          onclick="document.getElementById('image').click()"
        >
          <div class="space-y-1 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
              <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="text-sm text-gray-600">
              <span class="font-medium text-blue-600 hover:text-blue-500">Upload gambar baru</span>
              <span class="pl-1">atau drag and drop</span>
            </div>
            <p class="text-xs text-gray-500">PNG, JPG, JPEG, SVG hingga 10MB</p>
          </div>
        </div>
        @error('image')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        {{-- New Image Preview --}}
        <div id="preview-container" x-show="imagePreview" x-transition class="mt-4" style="display: none;">
          <p class="text-sm font-semibold text-gray-700 mb-2">Preview Gambar Baru:</p>
          <div class="relative bg-gray-100 rounded-lg p-4">
            <img id="preview-image" :src="imagePreview" alt="Preview" class="max-h-64 mx-auto rounded-lg shadow-md">
            <button type="button" @click="clearImage" onclick="clearImagePreview()" class="absolute top-2 right-2 p-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
        <textarea name="description" rows="3"
          placeholder="Deskripsi tambahan tentang denah ini (opsional)"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $floorPlan->description) }}</textarea>
        @error('description')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" name="is_active" value="1" {{ old('is_active', $floorPlan->is_active) ? 'checked' : '' }}
            class="rounded text-blue-600 focus:ring-blue-500">
          <span class="text-sm font-semibold text-gray-700">Floor Plan Aktif</span>
        </label>
        <p class="text-xs text-gray-500 mt-1">Hanya floor plan aktif yang akan ditampilkan di sistem</p>
      </div>

      <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md font-semibold">
          Update Floor Plan
        </button>
        <a href="{{ route('admin.floor-plans.index') }}" class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
          Batal
        </a>
      </div>
    </form>
  </div>

  <script>
    console.log('Floor plan edit script loaded');
    
    // Vanilla JS - Pure implementation
    function handleFileSelect(event) {
      console.log('File selected:', event.target.files[0]);
      const file = event.target.files[0];
      if (file) {
        console.log('Reading file...');
        const reader = new FileReader();
        reader.onload = function(e) {
          console.log('File loaded, showing preview');
          const previewContainer = document.getElementById('preview-container');
          const previewImage = document.getElementById('preview-image');
          
          if (previewContainer && previewImage) {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'block';
            console.log('Preview displayed');
          } else {
            console.error('Preview elements not found');
          }
        };
        reader.readAsDataURL(file);
      }
    }
    
    function clearImagePreview() {
      console.log('Clearing preview');
      const previewContainer = document.getElementById('preview-container');
      const imageInput = document.getElementById('image');
      
      if (previewContainer) {
        previewContainer.style.display = 'none';
      }
      if (imageInput) {
        imageInput.value = '';
      }
    }
    
    // Alpine.js component (if Alpine is loaded)
    function floorPlanForm() {
      return {
        imagePreview: null,
        
        previewImage(event) {
          console.log('Alpine preview triggered');
          const file = event.target.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
              this.imagePreview = e.target.result;
              console.log('Alpine preview set');
            };
            reader.readAsDataURL(file);
          }
        },
        
        clearImage() {
          this.imagePreview = null;
          document.getElementById('image').value = '';
        }
      }
    }
  </script>
</x-layouts.app>
