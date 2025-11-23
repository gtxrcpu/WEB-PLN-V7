{{-- Reusable Kartu Layout Component --}}
@props(['title', 'subtitle' => '', 'template', 'module', 'backRoute'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .sheet-a4 {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
            }
        }
    </style>
    
    <x-kartu-auto-refresh :module="$module" :template-version="$template ? $template->updated_at->timestamp : 0" />
</head>
<body class="bg-slate-50">

{{-- HEADER (NO PRINT) --}}
<div class="no-print bg-white border-b shadow-sm sticky top-0 z-10">
    <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-gray-900">{{ $title }}</h1>
            @if($subtitle)
                <p class="text-sm text-gray-600">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="flex gap-2">
            <a href="{{ route($backRoute) }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50 text-sm font-medium">
                ← Kembali
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                🖨️ Cetak
            </button>
        </div>
    </div>
</div>

{{-- CONTENT --}}
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="sheet-a4 bg-white rounded-xl shadow-lg border p-8">
        
        {{-- HEADER KARTU - FROM TEMPLATE --}}
        @if($template)
        <x-kartu-header :template="$template" />
        @else
        {{-- FALLBACK HEADER --}}
        <div class="flex items-start justify-between mb-6 pb-4 border-b-2 border-gray-200">
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $title }}</h2>
                @if($subtitle)
                <p class="text-sm text-gray-600">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        @endif

        {{ $slot }}

    </div>
</div>

</body>
</html>
