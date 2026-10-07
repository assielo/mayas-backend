@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-white">Administration des Terminaux Mobiles (MDM)</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="devices-grid">
        <!-- Généré dynamiquement via JS -->
    </div>
</div>
@endsection

@push('scripts')
<script>
    async function loadDevices() {
        const res = await fetch('/api/v1/admin/devices');
        const devices = await res.json();
        const grid = document.getElementById('devices-grid');
        grid.innerHTML = devices.map(d => `
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 space-y-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-bold text-white">${d.device_name}</h4>
                        <p class="text-xs text-slate-400">UUID: ${d.device_uuid}</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold ${d.status === 'ACTIVE' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400'}">
                        ${d.status}
                    </span>
                </div>
                <div class="text-xs text-slate-400 space-y-1">
                    <p><i class="fa-solid fa-battery-half mr-1"></i> Batterie: ${d.battery_level}%</p>
                    <p><i class="fa-solid fa-clock mr-1"></i> Dernier Signal: ${d.last_ping_at ?? 'N/A'}</p>
                </div>
                <div class="flex gap-2 pt-2 border-t border-slate-700">
                    <button onclick="updateDeviceStatus(${d.id}, 'LOCKED')" class="flex-1 bg-amber-600/20 text-amber-400 border border-amber-500/30 py-1.5 rounded text-xs font-medium">Verrouiller</button>
                    <button onclick="updateDeviceStatus(${d.id}, 'WIPED')" class="flex-1 bg-rose-600/20 text-rose-400 border border-rose-500/30 py-1.5 rounded text-xs font-medium">Effacer</button>
                </div>
            </div>
        `).join('');
    }

    async function updateDeviceStatus(id, action) {
        if(!confirm(`Confirmer l'action MDM : ${action} ?`)) return;
        await fetch(`/api/v1/admin/devices/${id}/status`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ action: action })
        });
        loadDevices();
    }
    loadDevices();
</script>
@endpush