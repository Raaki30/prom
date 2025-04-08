@php
    $steps = ['Pilih Siswa', 'Konfirmasi', 'Bayar'];
@endphp

<div class="max-w-2xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between text-sm font-medium text-gray-500">
        @foreach ($steps as $index => $label)
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full flex items-center justify-center
                    {{ $index + 1 <= $step ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }}">
                    {{ $index + 1 }}
                </div>
                <span class="hidden sm:inline">{{ $label }}</span>
            </div>
            @if (!$loop->last)
                <div class="flex-1 h-0.5 mx-2 {{ $index + 1 < $step ? 'bg-blue-400' : 'bg-gray-300' }}"></div>
            @endif
        @endforeach
    </div>
</div>
