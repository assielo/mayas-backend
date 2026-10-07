@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-slate-800 border border-slate-700 rounded-xl p-6 space-y-6">
    <h2 class="text-xl font-bold text-white border-b border-slate-700 pb-4">Exportation des Registres d'Accès</h2>

    <form action="/api/v1/reports/audit/download-csv" method="GET" class="space-y-4">
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-1">Identifiant du Site</label>
            <input type="number" name="site_id" value="1" required class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-sm text-white">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Date Début</label>
                <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-sm text-white">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Date Fin</label>
                <input type="date" name="end_date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-sm text-white">
            </div>
        </div>
        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2.5 rounded-lg text-sm transition flex items-center justify-center">
            <i class="fa-solid fa-file-csv mr-2"></i> Télécharger le Fichier CSV
        </button>
    </form>
</div>
@endsection