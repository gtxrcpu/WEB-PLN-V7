{{-- Reusable Kartu Header Component --}}
@props(['template'])

@if($template)
{{-- Company Header with Logos --}}
<div class="border-2 border-gray-800 mb-6">
    <div class="flex items-center justify-between p-4 border-b-2 border-gray-800">
        {{-- Logo PLN Kiri --}}
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logoo.png') }}" alt="PLN Logo" class="h-16 w-auto object-contain">
            <div class="text-left">
                @if($template->company_name)
                    <div class="font-bold text-sm">{{ $template->company_name }}</div>
                @endif
                @if($template->company_address)
                    <div class="text-xs">{{ $template->company_address }}</div>
                @endif
                @if($template->company_phone)
                    <div class="text-xs">{{ $template->company_phone }}</div>
                @endif
                @if($template->company_fax)
                    <div class="text-xs">{{ $template->company_fax }}</div>
                @endif
                @if($template->company_email)
                    <div class="text-xs">{{ $template->company_email }}</div>
                @endif
            </div>
        </div>
        
        {{-- 5 Logo Sertifikasi Kanan --}}
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo1.png') }}" alt="Cert 1" class="h-12 w-auto object-contain">
            <img src="{{ asset('images/logo2.png') }}" alt="Cert 2" class="h-12 w-auto object-contain">
            <img src="{{ asset('images/logo3.jpg') }}" alt="Cert 3" class="h-12 w-auto object-contain">
            <img src="{{ asset('images/logo4.png') }}" alt="Cert 4" class="h-12 w-auto object-contain">
            <img src="{{ asset('images/logo5.png') }}" alt="Cert 5" class="h-12 w-auto object-contain">
        </div>
    </div>
    
    {{-- Title & Document Info --}}
    <table class="w-full text-sm">
        <tr>
            <td rowspan="{{ count($template->header_fields) }}" class="border-r-2 border-gray-800 p-4 text-center align-middle w-2/3">
                <div class="font-bold text-2xl">{{ $template->title }}</div>
                <div class="font-semibold text-lg mt-2">{{ $template->subtitle }}</div>
                <div class="font-semibold text-base">TAHUN {{ date('Y') }}</div>
            </td>
            @php
                $firstField = $template->header_fields[0] ?? null;
            @endphp
            @if($firstField)
                <td class="border-r border-b border-gray-800 p-2 font-semibold bg-gray-100 w-1/6">{{ $firstField['label'] }}</td>
                <td class="border-b border-gray-800 p-2">{{ $firstField['value'] }}</td>
            @endif
        </tr>
        @foreach($template->header_fields as $index => $field)
            @if($index > 0)
                <tr>
                    <td class="border-r @if($index < count($template->header_fields) - 1) border-b @endif border-gray-800 p-2 font-semibold bg-gray-100">{{ $field['label'] }}</td>
                    <td class="@if($index < count($template->header_fields) - 1) border-b @endif border-gray-800 p-2">{{ $field['value'] }}</td>
                </tr>
            @endif
        @endforeach
    </table>
</div>
@endif
