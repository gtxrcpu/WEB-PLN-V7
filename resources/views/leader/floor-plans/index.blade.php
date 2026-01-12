<x-layouts.app :title="'Denah Lokasi — Leader'">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50">
        <!-- Header -->
        <div class="bg-white/80 backdrop-blur-lg border-b border-slate-200 sticky top-16 z-30">
            <div class="max-w-7xl mx-auto px-4 py-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('leader.dashboard') }}"
                            class="w-10 h-10 bg-slate-100 hover:bg-slate-200 rounded-xl flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-slate-800">Kelola Denah Lokasi</h1>
                            <p class="text-sm text-slate-500">Atur posisi peralatan pada denah unit Anda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 py-6">
            @if($floorPlans->isEmpty())
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Denah Belum Tersedia</h3>
                    <p class="text-slate-500 max-w-md mx-auto">
                        Silakan hubungi administrator untuk mengunggah denah unit Anda.
                    </p>
                </div>
            @else
                <!-- Floor Plans Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($floorPlans as $floorPlan)
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow">
                            <!-- Floor Plan Image Preview -->
                            <div class="relative h-48 bg-slate-100">
                                <img src="{{ $floorPlan->image_url }}" alt="{{ $floorPlan->name }}"
                                    class="w-full h-full object-cover">
                                @if($floorPlan->is_active)
                                    <div class="absolute top-3 right-3">
                                        <span
                                            class="px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded-lg">Aktif</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Floor Plan Info -->
                            <div class="p-4">
                                <h3 class="font-bold text-lg text-slate-800 mb-1">{{ $floorPlan->name }}</h3>

                                {{-- Unit Badge --}}
                                <div class="mb-3">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg 
                                                {{ $floorPlan->unit?->code === 'INDUK' ? 'bg-purple-100 text-purple-700 ring-1 ring-purple-200' : 'bg-teal-100 text-teal-700 ring-1 ring-teal-200' }}">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $floorPlan->unit?->name ?? 'Induk' }}
                                    </span>
                                </div>

                                @if($floorPlan->description)
                                    <p class="text-sm text-slate-500 mb-3">{{ $floorPlan->description }}</p>
                                @endif

                                <!-- Action Button -->
                                <a href="{{ route('leader.floor-plans.placement', $floorPlan) }}"
                                    class="inline-flex items-center gap-2 w-full justify-center px-4 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-xl font-semibold hover:from-indigo-600 hover:to-purple-600 transition-all shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Atur Lokasi Peralatan
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>