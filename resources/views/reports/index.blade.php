@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8 space-y-6" x-data="{ openModal: false }">

    <!-- En-tête et Bouton d'action -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white tracking-tight">Registre des Incidents & Sécurité</h1>
        <button @click="openModal = true" type="button" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-lg shadow-red-600/30 transition transform active:scale-95">
            + Signaler un Incident
        </button>
    </div>

    <!-- Notification de succès -->
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tableau d'affichage -->
    <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl overflow-hidden shadow-2xl">
        <table class="w-full text-left text-sm text-slate-300">
            <thead class="bg-slate-900/60 text-xs uppercase text-slate-400 border-b border-slate-700">
                <tr>
                    <th class="p-4">Date & Heure</th>
                    <th class="p-4">Titre / Urgence</th>
                    <th class="p-4">Site</th>
                    <th class="p-4">Agent Rapporteur</th>
                    <th class="p-4">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                @forelse($incidents ?? [] as $incident)
                <tr class="hover:bg-slate-700/30 transition">
                    <td class="p-4 font-mono text-xs">{{ $incident->created_at->format('Y-m-d H:i:s') }}</td>
                    <td class="p-4 font-semibold text-white">
                        {{ $incident->titre }}
                        <span class="ml-2 text-xs px-2 py-0.5 rounded font-bold {{ $incident->niveau == 'CRITIQUE' ? 'bg-red-500/20 text-red-400' : 'bg-amber-500/20 text-amber-400' }}">
                            {{ $incident->niveau }}
                        </span>
                    </td>
                    <td class="p-4">{{ $incident->site_id }}</td>
                    <td class="p-4">{{ $incident->agent_rapporteur }}</td>
                    <td class="p-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            {{ $incident->statut }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-400 font-medium">
                        Aucun incident signalé sur les dernières 24 heures.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Fenêtre Modale de Signalement -->
    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm" x-cloak>
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 w-full max-w-lg shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-700 pb-3">
                <h3 class="text-lg font-bold text-white">Nouveau Signalement d'Incident</h3>
                <button @click="openModal = false" class="text-slate-400 hover:text-white">&times;</button>
            </div>

            <form action="{{ route('reports.export') }}" method="POST" class="space-y-4">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-semibold text-slate-400 mb-1">SITE D'EXPLOITATION</label>
            <select name="site_id" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-indigo-500">
                <option value="all">Tous les sites</option>
                @foreach($sites as $site)
                    <option value="{{ $site->id }}">{{ $site->nom }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-400 mb-1">DATE DE DÉBUT</label>
            <input type="date" id="date_debut" name="date_debut" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-white">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-400 mb-1">DATE DE FIN</label>
            <input type="date" id="date_fin" name="date_fin" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-white">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-400 mb-1">FORMAT D'EXPORT</label>
            <select name="format" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white">
                <option value="csv">Fichier CSV (.csv)</option>
            </select>
        </div>
    </div>

    <div class="flex justify-end pt-2">
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-xl shadow-lg shadow-indigo-600/30 transition">
            Exporter le Registre
        </button>
    </div>
</form>
        </div>
    </div>

</div>


    <!-- En-tête de section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center gap-3">
                <span class="p-2 bg-indigo-600/20 text-indigo-400 rounded-xl border border-indigo-500/30">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                Centre d'Audits & Exportations
            </h1>
            <p class="text-slate-400 text-sm mt-1">Générez, filtrez et analysez les registres d'accès et logs de contrôle du terrain.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Base de données synchrone
            </span>
        </div>
    </div>

    <!-- Cartes de Statistiques Rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-slate-800/60 backdrop-blur-md border border-slate-700/60 rounded-2xl p-5 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-sm font-medium">Total Scans (24h)</span>
                <span class="p-2 bg-blue-500/10 text-blue-400 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></span>
            </div>
            <p class="text-3xl font-bold text-white mt-2">1,248</p>
            <span class="text-xs text-emerald-400 font-medium mt-1 inline-block">↑ +12% par rapport à hier</span>
        </div>

        <div class="bg-slate-800/60 backdrop-blur-md border border-slate-700/60 rounded-2xl p-5 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-sm font-medium">Anomalies Détectées</span>
                <span class="p-2 bg-amber-500/10 text-amber-400 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></span>
            </div>
            <p class="text-3xl font-bold text-white mt-2">3</p>
            <span class="text-xs text-slate-400 font-medium mt-1 inline-block">Nécessite vérification</span>
        </div>

        <div class="bg-slate-800/60 backdrop-blur-md border border-slate-700/60 rounded-2xl p-5 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-sm font-medium">Sites Actifs</span>
                <span class="p-2 bg-indigo-500/10 text-indigo-400 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4"/></svg></span>
            </div>
            <p class="text-3xl font-bold text-white mt-2">12</p>
            <span class="text-xs text-indigo-400 font-medium mt-1 inline-block">Tous terminaux connectés</span>
        </div>
    </div>

    <!-- Panneau Principal d'Exportation -->
    <div class="bg-slate-800/80 backdrop-blur-xl border border-slate-700/80 rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-slate-700/60 bg-slate-900/40 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-white">Filtres de Génération de Rapport</h2>
                <p class="text-xs text-slate-400">Configurez les paramètres ci-dessous pour extraire les logs au format souhaité.</p>
            </div>

            <!-- Boutons de raccourcis temporels -->
            <div class="flex items-center gap-2 bg-slate-900/80 p-1.5 rounded-xl border border-slate-700/50">
                <button type="button" onclick="setPeriod('today')" class="px-3 py-1.5 text-xs font-medium text-slate-300 hover:text-white rounded-lg hover:bg-slate-800 transition">Aujourd'hui</button>
                <button type="button" onclick="setPeriod('7days')" class="px-3 py-1.5 text-xs font-medium text-slate-300 hover:text-white rounded-lg hover:bg-slate-800 transition">7 jours</button>
                <button type="button" onclick="setPeriod('30days')" class="px-3 py-1.5 text-xs font-medium text-slate-300 hover:text-white rounded-lg hover:bg-slate-800 transition">30 jours</button>
            </div>
        </div>

        <form action="{{ route('reports.export') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Sélection du Site -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Site d'Exploitation</label>
                    <select name="site_id" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">
                        <option value="1">Site Central (Siège)</option>
                        <option value="2">Entrepôt Bouaké</option>
                        <option value="3">Zone Industrielle</option>
                    </select>
                </div>

                <!-- Date Début -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Date de Début</label>
                    <input type="date" id="date_debut" name="date_debut" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">
                </div>

                <!-- Date Fin -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Date de Fin</label>
                    <input type="date" id="date_fin" name="date_fin" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">
                </div>

                <!-- Format d'exportation -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Format d'Export</label>
                    <select name="format" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">
                        <option value="csv">Fichier CSV (.csv)</option>
                        <option value="excel">Tableur Excel (.xlsx)</option>
                    </select>
                </div>
            </div>

            <!-- Actions d'Exportation -->
            <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-700/50">
                <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center gap-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white font-semibold px-8 py-3.5 rounded-xl transition duration-200 shadow-lg shadow-indigo-600/30 active:scale-[0.98]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Exporter le Registre
                </button>
            </div>
        </form>
    </div>

    <!-- Aperçu des Derniers Accès -->
    <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl overflow-hidden shadow-2xl mt-6">
    <div class="px-6 py-4 border-b border-slate-700">
        <h3 class="text-lg font-bold text-white">Aperçu Récent des Enregistrements</h3>
    </div>
    <table class="w-full text-left text-sm text-slate-300">
        <thead class="bg-slate-900/60 text-xs uppercase text-slate-400 border-b border-slate-700">
            <tr>
                <th class="p-4">HORODATAGE</th>
                <th class="p-4">AGENT / INTERVENANT</th>
                <th class="p-4">SITE</th>
                <th class="p-4">ACTION</th>
                <th class="p-4">STATUT</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-700/50">
            @forelse($recentLogs as $log)
            <tr class="hover:bg-slate-700/30 transition">
                <td class="p-4 font-mono text-xs">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                <td class="p-4 font-semibold text-white">{{ $log->agent_nom }}</td>
                <td class="p-4">{{ $log->site->nom ?? 'Non spécifié' }}</td>
                <td class="p-4">
                    <span class="px-3 py-1 rounded-lg text-xs font-medium bg-slate-700/60 text-indigo-300 border border-indigo-500/20">
                        {{ $log->action }}
                    </span>
                </td>
                <td class="p-4">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        {{ $log->statut }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-8 text-center text-slate-400 font-medium">
                    Aucun enregistrement d'accès trouvé en base de données.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<!-- Script interactif pour sélection dynamique des dates -->
<script>
    function setPeriod(type) {
        const today = new Date();
        const endDate = today.toISOString().split('T')[0];
        let startDate = new Date();

        if (type === 'today') {
            startDate = today;
        } else if (type === '7days') {
            startDate.setDate(today.getDate() - 7);
        } else if (type === '30days') {
            startDate.setDate(today.getDate() - 30);
        }

        document.getElementById('date_debut').value = startDate.toISOString().split('T')[0];
        document.getElementById('date_fin').value = endDate;
    }
</script>
@endsection