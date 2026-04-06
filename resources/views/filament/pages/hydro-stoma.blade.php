<x-filament-panels::page>
    <div class="space-y-8">

        {{-- ===== SECTION: REALTIME STAT CARDS ===== --}}
        <div>
            <div class="flex items-center gap-2 mb-4">
                <x-heroicon-o-eye style="width:1rem;height:1rem;" class="text-primary-500" />
                <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Data Realtime Terbaru</h2>
                @if($latest)
                    <span class="ml-auto text-xs text-gray-400 dark:text-gray-500 italic">
                        {{ $latest->created_at->format('d M Y, H:i:s') }} &middot; {{ $latest->created_at->diffForHumans() }}
                    </span>
                @endif
            </div>

            @if(!$latest)
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center py-14 text-center bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700">
                    <div class="rounded-full bg-gray-100 dark:bg-gray-700 p-4 mb-3">
                        <x-heroicon-o-eye style="width:1.75rem;height:1.75rem;" class="text-gray-400" />
                    </div>
                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">Belum Ada Data Stomata</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Data akan muncul setelah sistem merekam klasifikasi stomata.</p>
                </div>
            @else
                {{-- 2 Stat Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Card 1: Klasifikasi Kondisi Stomata --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-5 flex items-center gap-5">
                        <div class="rounded-xl p-4 flex-shrink-0" style="background:rgba(59,130,246,0.1);">
                            <x-heroicon-o-eye style="width:2rem;height:2rem;" class="text-blue-500" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Klasifikasi Kondisi Stomata</p>
                            <p class="text-xl font-bold text-gray-800 dark:text-white truncate">{{ $latest->stomata_condition }}</p>
                            @if($latest->image_path)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($latest->image_path) }}"
                                         alt="Stomata"
                                         class="h-10 w-16 object-cover rounded-lg border border-gray-200 dark:border-gray-600"
                                    />
                                </div>
                            @else
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 italic">Tidak ada gambar</p>
                            @endif
                        </div>
                    </div>

                    {{-- Card 2: Aktivasi Aktuator --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-5 flex items-center gap-5">
                        <div class="rounded-xl p-4 flex-shrink-0"
                             style="background:{{ $latest->is_actuator_on ? 'rgba(34,197,94,0.12)' : 'rgba(239,68,68,0.1)' }};">
                            @if($latest->is_actuator_on)
                                <x-heroicon-o-bolt style="width:2rem;height:2rem;" class="text-green-500" />
                            @else
                                <x-heroicon-o-power style="width:2rem;height:2rem;" class="text-red-500" />
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Aktivasi Aktuator</p>
                            <div class="flex items-center gap-2 mt-1">
                                @if($latest->is_actuator_on)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-bold text-white shadow" style="background:rgb(34,197,94);">
                                        <span class="rounded-full bg-white animate-pulse" style="width:7px;height:7px;display:inline-block;"></span>
                                        ON — Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-bold text-white shadow" style="background:rgb(239,68,68);">
                                        <span class="rounded-full bg-white" style="width:7px;height:7px;display:inline-block;"></span>
                                        OFF — Non-aktif
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                Kontrol aktuator berdasarkan kondisi stomata terkini
                            </p>
                        </div>
                    </div>

                </div>
            @endif
        </div>

        {{-- ===== SECTION: HISTORY TABLE ===== --}}
        <div>
            <div class="flex items-center gap-2 mb-3">
                <x-heroicon-o-table-cells style="width:1rem;height:1rem;" class="text-primary-500" />
                <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Riwayat Keseluruhan</h2>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                {{ $this->table }}
            </div>
        </div>

    </div>
</x-filament-panels::page>
