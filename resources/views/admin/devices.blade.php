@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">Administration des Terminaux Mobiles (MDM)</h1>
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-600/30">
            + Enregistrer un Terminal
        </button>
    </div>

    <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl overflow-hidden shadow-2xl">
        <table class="w-full text-left text-sm text-slate-300">
            <thead class="bg-slate-900/60 text-xs uppercase text-slate-400 border-b border-slate-700">
                <tr>
                    <th class="p-4">Identifiant Terminal</th>
                    <th class="p-4">Agent Actif</th>
                    <th class="p-4">Niveau Batterie</th>
                    <th class="p-4">Dernier Heartbeat</th>
                    <th class="p-4">État Synchro</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                @forelse($devices as $device)
                <tr class="hover:bg-slate-700/30 transition">
                    <td class="p-4 font-mono font-semibold text-white">{{ $device->device_uuid }}</td>
                    <td class="p-4">{{ $device->agent->name ?? 'Non assigné' }}</td>
                    <td class="p-4 font-mono">{{ $device->battery_level }}%</td>
                    <td class="p-4 font-mono text-xs">{{ $device->last_seen_at->diffForHumans() }}</td>
                    <td class="p-4">
                        @if($device->last_seen_at->gt(now()->subMinutes(5)))
                            <span class="inline-flex items-center gap-1.5 text-xs text-emerald-400"><span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> En Ligne</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs text-slate-400"><span class="w-2 h-2 rounded-full bg-slate-500"></span> Hors Ligne</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-slate-500">Aucun terminal MDM répertorié.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection