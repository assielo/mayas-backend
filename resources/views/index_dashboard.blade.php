<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SGBD Web - CHEPITIMAYAS SÉCURITÉ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <style>
        .badge-actif { background-color: #DEF7EC; color: #03543F; }
        .badge-inactif { background-color: #E1EFFE; color: #1E429F; }
        .badge-suspendu { background-color: #FEECDC; color: #B43403; }
        .nav-item-active { background-color: #f59e0b !important; color: #0f172a !important; font-weight: 700 !important; }
        @media print {
            body * { visibility: hidden; }
            #printableProfileCard, #printableProfileCard * { visibility: visible; }
            #printableProfileCard { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans antialiased">
    <div id="toast" class="fixed top-5 right-5 z-50 transform translate-x-full transition-transform duration-300 ease-in-out bg-slate-900 text-white px-4 py-3 rounded-lg shadow-xl flex items-center gap-3">
        <i id="toastIcon" class="fa-solid fa-circle-check text-amber-500"></i>
        <span id="toastMsg" class="text-sm font-medium">Action effectuée</span>
    </div>

    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col justify-between hidden md:flex">
            <div>
                <div class="p-5 text-center border-b border-slate-800 flex flex-col items-center">
                    <div class="w-16 h-16 bg-amber-500 rounded-2xl flex items-center justify-center text-slate-900 shadow-lg mb-3">
                        
                        <img src="{{ asset('images/logo-mayas.png') }}" alt="MAYAS Sécurité" class="relative w-12 h-12 object-contain rounded-lg p-0.5 bg-slate-950 border border-slate-800">
                    </div>
                    <h1 class="text-lg font-bold tracking-wider text-amber-500 uppercase">CHEPITIMAYAS SÉCURITÉ</h1>
                    <p class="text-xs text-slate-400">Gestion des agents </p>
                </div>
                <nav class="mt-6 px-3 space-y-2">
                    <button id="nav-dashboard" onclick="switchTab('dashboard')" class="nav-item nav-item-active w-full flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 rounded-lg transition font-medium text-left">
                        <i class="fa-solid fa-chart-line w-6"></i> Tableau de bord
                    </button>
                    <button id="nav-agents" onclick="switchTab('agents')" class="nav-item w-full flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 rounded-lg transition font-medium text-left">
                        <i class="fa-solid fa-user-shield w-6"></i> Gestion Agents
                    </button>
                    <button id="nav-sites" onclick="switchTab('sites')" class="nav-item w-full flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 rounded-lg transition font-medium text-left">
                        <i class="fa-solid fa-building w-6"></i> Sites & Clients
                    </button>
                    <button id="nav-postes" onclick="switchTab('postes')" class="nav-item w-full flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 rounded-lg transition font-medium text-left">
                        <i class="fa-solid fa-briefcase w-6"></i> Postes & Grille
                    </button>
                    <button id="nav-paie" onclick="switchTab('paie')" class="nav-item w-full flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 rounded-lg transition font-medium text-left">
                        <i class="fa-solid fa-file-invoice-dollar w-6"></i> Pré-Paie & Wave
                    </button>
                </nav>
            </div>
            
            <!-- DECONNEXION SIDEBAR -->
            <div class="p-4 border-t border-slate-800 space-y-3">
                <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Se déconnecter</button>
</form>
                <div class="text-[10px] text-slate-500 text-center">© 2026 CHEPITIMAYAS SÉCURITÉ. Tous droits réservés.

Développé par MIR GROUP

Modernité - Innovation - Réussitev.5</div>
            </div>
        </aside>

        <!-- MAIN -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <header class="bg-white shadow-sm px-6 py-4 flex flex-wrap justify-between items-center gap-4 border-b border-slate-200">
                <div class="flex items-center space-x-4">
                    <button class="md:hidden text-slate-700 text-xl"><i class="fa-solid fa-bars"></i></button>
                    <h2 id="pageTitle" class="text-xl font-black text-slate-900 tracking-tight uppercase">TABLEAU DE BORD DE SUIVI DES AGENTS</h2>
                </div>
                <div class="flex items-center space-x-3">
                    <button onclick="showToast('Ouverture du module Scanner...')" class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-2 shadow-sm transition">
                        <i class="fa-solid fa-qrcode text-base"></i> Pointage du scanner
                    </button>
                    <span class="px-3 py-2 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Connecté (386 agents)
                    </span>
                    <div class="flex items-center space-x-3 pl-3 border-l border-slate-200">
                        <div class="w-9 h-9 bg-amber-500 text-slate-900 font-black rounded-full flex items-center justify-center text-xs shadow-sm">ADMIN</div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-slate-800 leading-tight">Admin MAYAS</p>
                            <p class="text-[10px] text-slate-500">Superviseur RH</p>
                        </div>
                        <button onclick="deconnecterUtilisateur()" class="bg-red-600 hover:bg-red-700 text-white font-bold px-3 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition">
                            <i class="fa-solid fa-right-from-bracket"></i> 
                        </button>
                    </div>
                </div>
                <!-- En-tête Principal & Navigation Globale -->
<header class="bg-slate-800 border-b border-slate-700 px-6 py-4">
    <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
        
        <!-- Boutons de Redirection Modules (Navigation Principale) -->
        <nav class="flex flex-wrap items-center justify-center gap-2 bg-slate-900/60 p-1.5 rounded-xl border border-slate-700/80">
            
            <!-- Redirection 1 : Supervision Temps Réel -->
            <a href="/dashboard" 
               class="bg-indigo-600 text-white px-3.5 py-2 rounded-lg text-xs font-semibold flex items-center shadow-md transition">
                <i class="fa-solid fa-chart-line mr-2 text-indigo-200"></i> Supervision
            </a>

            <!-- Redirection 2 : Gestion des Véhicules & Parking -->
            <a href="/vehicles/manage" 
               class="text-slate-300 hover:text-white hover:bg-slate-800 px-3.5 py-2 rounded-lg text-xs font-medium transition flex items-center">
                <i class="fa-solid fa-car-rear mr-2 text-amber-400"></i> Véhicules & Parking
            </a>

            <!-- Redirection 3 : Signaler / Suivi Incidents -->
            <a href="/incidents" 
               class="text-slate-300 hover:text-white hover:bg-slate-800 px-3.5 py-2 rounded-lg text-xs font-medium transition flex items-center">
                <i class="fa-solid fa-triangle-exclamation mr-2 text-rose-400"></i> Incidents
            </a>

            <!-- Redirection 4 : Flotte MDM (Terminaux Mobile) -->
            <a href="/admin/devices" 
               class="text-slate-300 hover:text-white hover:bg-slate-800 px-3.5 py-2 rounded-lg text-xs font-medium transition flex items-center">
                <i class="fa-solid fa-mobile-screen-button mr-2 text-emerald-400"></i> Flotte MDM
            </a>

            <!-- Redirection 5 : Rapports & Audits -->
            <a href="/reports" 
               class="text-slate-300 hover:text-white hover:bg-slate-800 px-3.5 py-2 rounded-lg text-xs font-medium transition flex items-center">
                <i class="fa-solid fa-file-contract mr-2 text-blue-400"></i> Audits & Exports
            </a>
        </nav>

        <!-- Statut & Déconnexion -->
        <div class="flex items-center space-x-3">
            <span class="hidden xl:inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                <span class="w-2 h-2 mr-1.5 bg-emerald-400 rounded-full animate-pulse"></span> En Ligne
            </span>

            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" 
                        class="bg-slate-700 hover:bg-rose-600/20 hover:text-rose-400 text-slate-300 border border-slate-600 hover:border-rose-500/30 px-3 py-2 rounded-lg text-xs font-medium transition flex items-center">
                    <i class="fa-solid fa-power-off mr-1.5"></i> Déconnexion
                </button>
            </form>
        </div>

    </div>
</header>
            </header>

            <main class="p-6 space-y-6">
                <!-- RECHERCHE GLOBALE PARTAGÉE -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between border-b pb-2">
                        <h3 class="text-xs font-bold uppercase text-slate-500 tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-magnifying-glass text-amber-500"></i> Recherche & Filtrage Dynamique
                        </h3>
                        <button onclick="reinitialiserFiltres()" class="text-xs text-amber-600 hover:underline font-semibold">Réinitialiser les filtres</button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Agent / Matricule</label>
                            <input type="text" id="searchNom" oninput="filtrerTousLesTableaux()" placeholder="Rechercher nom, matricule..." class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-amber-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">N° Wave</label>
                            <input type="text" id="searchWave" oninput="filtrerTousLesTableaux()" placeholder="Numéro Wave..." class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-amber-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Site</label>
                            <select id="searchSite" onchange="filtrerTousLesTableaux()" class="w-full border border-slate-300 rounded-lg px-2 py-2 text-xs focus:outline-none focus:border-amber-500">
                                <option value="">Tous les sites</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Rôle</label>
                            <select id="searchRole" onchange="filtrerTousLesTableaux()" class="w-full border border-slate-300 rounded-lg px-2 py-2 text-xs focus:outline-none focus:border-amber-500">
                                <option value="">Tous les rôles</option>
                                <option value="AGENT">AGENT</option>
                                <option value="SUPERVISEUR">SUPERVISEUR</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Statut</label>
                            <select id="searchStatut" onchange="filtrerTousLesTableaux()" class="w-full border border-slate-300 rounded-lg px-2 py-2 text-xs focus:outline-none focus:border-amber-500">
                                <option value="">Tous les statuts</option>
                                <option value="Actif">Actif</option>
                                <option value="Inactif">Inactif</option>
                                <option value="Suspendu">Suspendu</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ONGLET 1: DASHBOARD -->
                <div id="view-dashboard" class="tab-content space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200"><p class="text-xs font-bold text-slate-500">Agents totaux</p><p class="text-3xl font-black text-slate-900 mt-2" id="stat-total">386</p></div>
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200"><p class="text-xs font-bold text-slate-500">Agents Actifs</p><p class="text-3xl font-black text-emerald-600 mt-2" id="stat-actifs">375</p></div>
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200"><p class="text-xs font-bold text-slate-500">Masse Salariale (Actifs)</p><p class="text-2xl font-black text-slate-900 mt-2" id="stat-masse">26 463 832 FCFA</p></div>
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200"><p class="text-xs font-bold text-slate-500">Superviseurs</p><p class="text-3xl font-black text-amber-500 mt-2" id="stat-superviseurs">0</p></div>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-wrap gap-3 justify-between items-center">
                        <button onclick="ouvrirModaleAjoutAgent()" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2.5 rounded-lg text-sm flex items-center gap-2 shadow"><i class="fa-solid fa-user-plus text-amber-500"></i> Nouvel Agent</button>
                        <div class="flex gap-2">
                            <button onclick="exporterWave()" class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2.5 rounded-lg text-sm font-bold flex items-center gap-2 shadow"><i class="fa-solid fa-mobile-screen-button"></i> Exporter Wave (.CSV)</button>
                            <button onclick="exporterExcelComplet()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg text-sm font-bold flex items-center gap-2 shadow"><i class="fa-solid fa-file-excel"></i> Exporter Excel (.XLSX)</button>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b text-slate-700 font-bold text-xs uppercase">
                                    <th class="p-4">Matrice</th><th class="p-4">Rôle</th><th class="p-4">Noms et Prénoms</th><th class="p-4">N° Pièce</th><th class="p-4">N° Wave</th><th class="p-4">Site</th><th class="p-4">Salaire</th><th class="p-4">Statut</th><th class="p-4 text-center">Actes</th>
                                </tr>
                            </thead>
                            <tbody id="agentTableBody" class="divide-y divide-slate-100"></tbody>
                        </table>
                    </div>
                </div>

                <!-- ONGLET 2: GESTION AGENTS -->
                <div id="view-agents" class="tab-content hidden space-y-6">
                    <div id="agentsGrid" class="grid grid-cols-1 md:grid-cols-3 gap-4"></div>
                </div>

                <!-- ONGLET 3: SITES -->
                <div id="view-sites" class="tab-content hidden space-y-6">
                    <div id="sitesList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
                </div>

                <!-- ONGLET 4: POSTES -->
                <div id="view-postes" class="tab-content hidden space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto p-4">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b">
                                    <th class="p-3 font-semibold text-slate-700">Site d'affectation</th>
                                    <th class="p-3 font-semibold text-slate-700">Poste Jour (06h-18h)</th>
                                    <th class="p-3 font-semibold text-slate-700">Poste Nuit (18h-06h)</th>
                                    <th class="p-3 font-semibold text-slate-700 text-center">Effectif Total</th>
                                </tr>
                            </thead>
                            <tbody id="grillePostesTableBody" class="divide-y divide-slate-100"></tbody>
                        </table>
                    </div>
                </div>

                <!-- ONGLET 5: PRÉ-PAIE & WAVE -->
                <div id="view-paie" class="tab-content hidden space-y-6">
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex justify-between items-center">
                        <h3 class="font-bold text-slate-800">Module de Pré-Paie & Génération du Fichier Wave</h3>
                        <button onclick="exporterWave()" class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 shadow">
                            <i class="fa-solid fa-mobile-screen-button"></i> Exporter Wave (.CSV)
                        </button>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 border-b">
                                <tr>
                                    <th class="p-4">Matricule</th><th class="p-4">Nom & Prénoms</th><th class="p-4">N° Wave</th><th class="p-4">Base + Primes</th><th class="p-4 text-center">Ajuster Prime</th><th class="p-4">Statut Virement</th><th class="p-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="waveTableBody" class="divide-y divide-slate-100"></tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- MODALE FICHE PROFIL AVEC IMPRESSION -->
    <div id="profileModal" class="fixed inset-0 bg-slate-900/60 hidden flex items-center justify-center p-4 z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-xl w-full overflow-hidden" id="printableProfileCard">
            <div class="bg-slate-900 p-6 text-white flex justify-between items-start">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-full border-2 border-amber-500 bg-slate-700 flex items-center justify-center text-amber-500 font-bold text-2xl">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <div>
                        <h3 id="viewNomPrenom" class="text-xl font-bold text-white">-</h3>
                        <p id="viewRole" class="text-xs font-semibold text-amber-500 uppercase">-</p>
                        <p id="viewMatricule" class="text-xs text-slate-400 font-mono">-</p>
                    </div>
                </div>
                <button onclick="toggleProfileModal(false)" class="text-slate-400 hover:text-white no-print"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            <div class="p-6 space-y-4 text-slate-700 text-sm">
                <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border">
                    <div><p class="text-xs text-slate-400 uppercase">Site</p><p id="viewSite" class="font-bold text-slate-800">-</p></div>
                    <div><p class="text-xs text-slate-400 uppercase">Statut</p><p id="viewStatut" class="font-bold text-emerald-600">-</p></div>
                    <div><p class="text-xs text-slate-400 uppercase">Salaire</p><p id="viewSalaire" class="font-bold text-slate-800">0 FCFA</p></div>
                    <div><p class="text-xs text-slate-400 uppercase">Sexe</p><p id="viewSexe" class="font-bold text-slate-800">-</p></div>
                </div>
                <div class="space-y-2">
                    <p>N° Pièce : <strong id="viewPiece" class="font-mono text-slate-800">-</strong></p>
                    <p>N° Wave : <strong id="viewWave" class="font-mono text-slate-800">-</strong></p>
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-4 border-t flex justify-between items-center no-print">
                <button onclick="imprimerFicheAgent()" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold rounded-lg text-xs flex items-center gap-2">
                    <i class="fa-solid fa-print"></i> Imprimer la Fiche
                </button>
                <button onclick="toggleProfileModal(false)" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg text-xs">Fermer</button>
            </div>
        </div>
    </div>

    <!-- MODALE NOUVEL AGENT -->
    <div id="modalAjoutAgent" class="fixed inset-0 bg-slate-900/60 hidden flex items-center justify-center p-4 z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden">
            <div class="bg-slate-900 p-5 text-white flex justify-between items-center">
                <h3 class="text-lg font-bold">Enregistrement Agent</h3>
                <button onclick="fermerModaleAjoutAgent()" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            <form id="formAjoutAgent" onsubmit="ajouterNouvelAgent(event)" class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Rôle *</label>
                        <select id="addRole" onchange="genererMatriculeAuto()" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="AGENT">AGENT</option>
                            <option value="SUPERVISEUR">SUPERVISEUR</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Matricule</label>
                        <input type="text" id="addMatricule" readonly value="Généré automatiquement" class="w-full border bg-slate-100 rounded-lg px-3 py-2 text-sm font-mono font-bold text-amber-600">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Nom & Prénoms *</label>
                        <input type="text" id="addNomPrenom" required placeholder="Nom complet" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Sexe *</label>
                        <select id="addSexe" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="Masculin">Masculin</option>
                            <option value="Féminin">Féminin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">N° Pièce *</label>
                        <input type="text" id="addPiece" required placeholder="CI..." class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">N° Wave *</label>
                        <input type="tel" id="addWave" required placeholder="07..." class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Site *</label>
                        <select id="addSite" required class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Chargement des sites...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Vacation *</label>
                        <select id="addVacation" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="Jour">Jour (06h-18h)</option>
                            <option value="Nuit">Nuit (18h-06h)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Salaire (FCFA) *</label>
                        <input type="number" id="addSalaire" required min="50000" step="5000" value="110000" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Statut *</label>
                        <select id="addStatut" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="Actif">Actif</option>
                            <option value="Inactif">Inactif</option>
                            <option value="Suspendu">Suspendu</option>
                        </select>
                    </div>
                </div>
                <div class="pt-4 border-t flex justify-end gap-3">
                    <button type="button" onclick="fermerModaleAjoutAgent()" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg text-sm">Annuler</button>
                    <button type="submit" class="px-5 py-2 bg-amber-500 text-slate-900 font-bold rounded-lg text-sm shadow">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JS LOGIC -->
    <script>
        // Les données réelles sont chargées depuis la BDD au démarrage
        // (voir chargerDonneesDepuisBDD / chargerSites plus bas).
        let agentsData = [];
        let sitesData = [];

        function csrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        function deconnecterUtilisateur() {
            showToast('Déconnexion en cours...');
            localStorage.removeItem('user_token');
            sessionStorage.clear();
            setTimeout(() => {
                window.location.href = "/login";
            }, 1200);
        }

        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('nav-item-active'));
            document.getElementById('view-' + tabId).classList.remove('hidden');
            document.getElementById('nav-' + tabId).classList.add('nav-item-active');
        }

        function filtrerTousLesTableaux() {
            const queryNom = document.getElementById('searchNom').value.toLowerCase().trim();
            const queryWave = document.getElementById('searchWave').value.trim();
            const siteFilter = document.getElementById('searchSite').value;
            const roleFilter = document.getElementById('searchRole').value;
            const statutFilter = document.getElementById('searchStatut').value;

            const res = agentsData.filter(a => {
                const mNom = !queryNom || a.nom.toLowerCase().includes(queryNom) || a.matricule.toLowerCase().includes(queryNom);
                const mWave = !queryWave || a.wave.includes(queryWave);
                const mSite = !siteFilter || a.site === siteFilter;
                const mRole = !roleFilter || a.role === roleFilter;
                const mStatut = !statutFilter || a.statut === statutFilter;
                return mNom && mWave && mSite && mRole && mStatut;
            });

            renderDashboardTable(res);
            renderAgentsGrid(res);
            renderWaveTable(res);
        }

        function reinitialiserFiltres() {
            document.getElementById('searchNom').value = '';
            document.getElementById('searchWave').value = '';
            document.getElementById('searchSite').value = '';
            document.getElementById('searchRole').value = '';
            document.getElementById('searchStatut').value = '';
            filtrerTousLesTableaux();
        }

        function renderAll() {
            renderDashboardTable(agentsData);
            renderAgentsGrid(agentsData);
            renderSitesList();
            renderGrillePostes();
            renderWaveTable(agentsData);
        }

        function renderDashboardTable(data) {
            const tbody = document.getElementById('agentTableBody');
            tbody.innerHTML = '';
            data.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = "hover:bg-slate-50 border-b";
                tr.innerHTML = `
                    <td class="p-4 font-mono text-xs font-bold">${item.matricule}</td>
                    <td class="p-4"><span class="text-xs px-2 py-1 rounded bg-slate-100 font-semibold">${item.role}</span></td>
                    <td class="p-4 font-semibold">${item.nom}</td>
                    <td class="p-4 text-xs font-mono">${item.piece}</td>
                    <td class="p-4 text-xs font-mono">${item.wave}</td>
                    <td class="p-4">${item.site}</td>
                    <td class="p-4 font-bold">${item.salaire.toLocaleString()} FCFA</td>
                    <td class="p-4"><span class="px-2.5 py-1 rounded-full text-xs font-bold ${item.statut === 'Actif' ? 'badge-actif' : 'badge-suspendu'}">${item.statut}</span></td>
                    <td class="p-4 text-center">
                        <button onclick="afficherProfil(${item.id})" class="text-amber-600 hover:text-amber-700 px-2 py-1 font-semibold text-xs flex items-center gap-1 mx-auto">
                            <i class="fa-solid fa-eye"></i> Voir Fiche
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function renderAgentsGrid(data) {
            const grid = document.getElementById('agentsGrid');
            grid.innerHTML = '';
            data.forEach(item => {
                const card = document.createElement('div');
                card.className = "bg-white p-5 rounded-xl border shadow-sm flex flex-col justify-between";
                card.innerHTML = `
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-mono bg-slate-100 px-2 py-1 rounded font-bold">${item.matricule}</span>
                            <span class="text-xs px-2 py-1 rounded-full font-bold ${item.statut === 'Actif' ? 'badge-actif' : 'badge-suspendu'}">${item.statut}</span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-base">${item.nom}</h4>
                        <p class="text-xs text-amber-600 font-bold uppercase mb-3">${item.role}</p>
                        <div class="text-xs space-y-1 text-slate-500">
                            <p><i class="fa-solid fa-building mr-1"></i> ${item.site}</p>
                            <p><i class="fa-solid fa-mobile-screen mr-1"></i> Wave: ${item.wave}</p>
                        </div>
                    </div>
                    <button onclick="afficherProfil(${item.id})" class="mt-4 w-full py-2 bg-slate-100 hover:bg-amber-500 font-bold rounded-lg text-xs transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-id-card"></i> Voir & Imprimer Fiche
                    </button>
                `;
                grid.appendChild(card);
            });
        }

        function renderSitesList() {
            const sites = sitesData.map(s => s.nom_site);
            const container = document.getElementById('sitesList');
            container.innerHTML = '';
            sites.forEach(site => {
                const count = agentsData.filter(a => a.site === site && a.statut === 'Actif').length;
                const card = document.createElement('div');
                card.className = "bg-white p-5 rounded-xl border shadow-sm";
                card.innerHTML = `
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <div><h4 class="font-bold text-slate-800">${site}</h4><p class="text-xs text-slate-400">Contrat Client</p></div>
                    </div>
                    <div class="pt-3 border-t flex justify-between items-center text-xs">
                        <span class="text-slate-500">Agents actifs</span>
                        <span class="font-bold bg-slate-900 text-white px-2.5 py-1 rounded-full">${count}</span>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function renderGrillePostes() {
            const sites = sitesData.map(s => s.nom_site);
            const tbody = document.getElementById('grillePostesTableBody');
            tbody.innerHTML = '';
            sites.forEach(site => {
                const list = agentsData.filter(a => a.site === site && a.statut === 'Actif');
                const j = list.filter(a => a.vacation === 'Jour').length;
                const n = list.filter(a => a.vacation === 'Nuit').length;
                const tr = document.createElement('tr');
                tr.className = "border-b";
                tr.innerHTML = `
                    <td class="p-3 font-semibold">${site}</td>
                    <td class="p-3 text-slate-600">${j} agent(s)</td>
                    <td class="p-3 text-slate-600">${n} agent(s)</td>
                    <td class="p-3 text-center font-bold text-amber-600">${list.length}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        function renderWaveTable(data) {
            const tbody = document.getElementById('waveTableBody');
            tbody.innerHTML = '';
            data.filter(a => a.statut === 'Actif').forEach(item => {
                const total = item.salaire + (item.prime || 0);
                const tr = document.createElement('tr');
                tr.className = "border-b";
                tr.innerHTML = `
                    <td class="p-4 font-mono text-xs font-bold">${item.matricule}</td>
                    <td class="p-4 font-semibold">${item.nom}</td>
                    <td class="p-4 font-mono text-xs">${item.wave}</td>
                    <td class="p-4 font-bold text-slate-900">${total.toLocaleString()} FCFA</td>
                    <td class="p-4 text-center">
                        <input type="number" value="${item.prime || 0}" onchange="updatePrime(${item.id}, this.value)" class="w-24 border rounded px-2 py-1 text-xs text-center">
                    </td>
                    <td class="p-4"><span class="px-2 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded">Prêt</span></td>
                    <td class="p-4 text-center">
                        <button onclick="afficherProfil(${item.id})" class="text-amber-600 hover:text-amber-700 px-2 py-1 font-semibold text-xs flex items-center gap-1 mx-auto">
                            <i class="fa-solid fa-id-card"></i> Fiche
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function afficherProfil(id) {
            const agent = agentsData.find(a => a.id === id);
            if (!agent) return;
            document.getElementById('viewNomPrenom').innerText = agent.nom;
            document.getElementById('viewRole').innerText = agent.role;
            document.getElementById('viewMatricule').innerText = agent.matricule;
            document.getElementById('viewSite').innerText = agent.site;
            document.getElementById('viewStatut').innerText = agent.statut;
            document.getElementById('viewSalaire').innerText = agent.salaire.toLocaleString() + ' FCFA';
            document.getElementById('viewSexe').innerText = agent.sexe;
            document.getElementById('viewPiece').innerText = agent.piece;
            document.getElementById('viewWave').innerText = agent.wave;
            toggleProfileModal(true);
        }

        function imprimerFicheAgent() { window.print(); }
        function toggleProfileModal(s) { document.getElementById('profileModal').classList.toggle('hidden', !s); }
        function ouvrirModaleAjoutAgent() { genererMatriculeAuto(); document.getElementById('modalAjoutAgent').classList.remove('hidden'); }
        function fermerModaleAjoutAgent() { document.getElementById('modalAjoutAgent').classList.add('hidden'); }

        function genererMatriculeAuto() {
            // Le matricule est désormais généré côté serveur à l'enregistrement
            // (voir Agent::booted côté Laravel), pour rester cohérent avec les
            // agents déjà en base. On se contente d'afficher un texte indicatif.
            document.getElementById('addMatricule').value = 'Généré automatiquement';
        }

        async function ajouterNouvelAgent(e) {
            e.preventDefault();

            const sexeLabel = document.getElementById('addSexe').value; // Masculin / Féminin
            const payload = {
                nom_prenom: document.getElementById('addNomPrenom').value,
                sexe: sexeLabel === 'Féminin' ? 'F' : 'M',
                numero_piece: document.getElementById('addPiece').value || null,
                numero_wave: document.getElementById('addWave').value,
                salaire: parseInt(document.getElementById('addSalaire').value) || 0,
                statut: document.getElementById('addStatut').value,
                site_id: document.getElementById('addSite').value || null,
                // Le formulaire ne collecte pas encore de date de début :
                // on prend la date du jour par défaut. À faire évoluer si besoin
                // d'une vraie date d'embauche saisissable.
                date_debut: new Date().toISOString().slice(0, 10),
            };

            try {
                const response = await fetch('/api/agents', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken(),
                    },
                    body: JSON.stringify(payload),
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    const detail = result.errors ? Object.values(result.errors).flat().join('\n') : (result.message || 'Erreur inconnue');
                    throw new Error(detail);
                }

                fermerModaleAjoutAgent();
                document.getElementById('formAjoutAgent').reset();
                await chargerDonneesDepuisBDD();
                showToast('Agent enregistré avec succès');
            } catch (error) {
                console.error(error);
                alert("Impossible d'enregistrer l'agent :\n" + error.message);
            }
        }

        function updatePrime(id, val) {
            const a = agentsData.find(x => x.id === id);
            if (a) { a.prime = parseInt(val) || 0; showToast('Prime mise à jour'); renderWaveTable(agentsData); }
        }

        function exporterWave() {
            let csv = "Matricule,Nom,Wave,Montant\n";
            agentsData.filter(a => a.statut === 'Actif').forEach(a => {
                csv += `"${a.matricule}","${a.nom}","${a.wave}",${a.salaire + (a.prime || 0)}\n`;
            });
            const blob = new Blob([csv], { type: 'text/csv' });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'Export_Wave.csv';
            a.click();
            showToast('Fichier CSV Wave généré');
        }

        function exporterExcelComplet() {
            const ws = XLSX.utils.json_to_sheet(agentsData);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Agents");
            XLSX.writeFile(wb, "Chepitimayas_Agents.xlsx");
        }

        function showToast(m) {
            const t = document.getElementById('toast');
            document.getElementById('toastMsg').innerText = m;
            t.classList.remove('translate-x-full');
            setTimeout(() => t.classList.add('translate-x-full'), 3000);
        }

        async function chargerSites() {
            try {
                const response = await fetch('/api/sites', { headers: { 'Accept': 'application/json' } });
                const result = await response.json();

                if (result.success && Array.isArray(result.data)) {
                    sitesData = result.data;

                    const addSelect = document.getElementById('addSite');
                    addSelect.innerHTML = '<option value="">-- Sélectionner un site --</option>' +
                        sitesData.map(s => `<option value="${s.id}">${s.nom_site}</option>`).join('');

                    const filterSelect = document.getElementById('searchSite');
                    filterSelect.innerHTML = '<option value="">Tous les sites</option>' +
                        sitesData.map(s => `<option value="${s.nom_site}">${s.nom_site}</option>`).join('');
                }
            } catch (error) {
                console.error('Impossible de charger les sites :', error);
            }
        }

        async function chargerDonneesDepuisBDD() {
            try {
                // Chemin relatif : fonctionne quel que soit le domaine/port utilisé.
                const response = await fetch('/api/agents', { headers: { 'Accept': 'application/json' } });

                if (!response.ok) {
                    throw new Error(`Erreur serveur (Code HTTP: ${response.status})`);
                }

                const result = await response.json();

                if (result.success && Array.isArray(result.data)) {
                    agentsData = result.data.map(agent => ({
                        id: agent.id,
                        matricule: agent.matricule || "N/A",
                        nom: agent.nom_prenom || "Sans nom",
                        prenom: "",
                        sexe: agent.sexe === 'F' ? 'Féminin' : 'Masculin',
                        piece: agent.numero_piece || "-",
                        wave: agent.numero_wave || "-",
                        site: agent.site_nom || "Non assigné",
                        site_id: agent.site_id,
                        vacation: "-", // pas encore de colonne "vacation" en base
                        statut: agent.statut || "Actif",
                        salaire: parseFloat(agent.salaire) || 0,
                        prime: 0, // pas encore de colonne "prime" en base (calcul Wave à affiner si besoin)
                        role: "Agent"
                    }));

                    renderAll();
                } else {
                    alert("Erreur BDD : " + result.message);
                }

            } catch (error) {
                console.error("Détail de l'erreur :", error);
                alert("Impossible de charger les données !\nDétail : " + error.message);
            }
        }

        async function initDashboard() {
            await chargerSites();
            await chargerDonneesDepuisBDD();
        }

        window.onload = initDashboard;
    </script>
</body>
</html>