<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGBD Web - CHEPITIMAYAS SÉCURITÉ (avec Scanner QR)</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- html2pdf.js et dépendances -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SheetJS (Excel) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <!-- html5-qrcode (Scanner Caméra) -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        .badge-actif { background-color: #DEF7EC; color: #03543F; }
        .badge-inactif { background-color: #E1EFFE; color: #1E429F; }
        .badge-suspendu { background-color: #FEECDC; color: #B43403; }
        .badge-rupture { background-color: #FDE8E8; color: #9B1C1C; }
        .nav-item-active {
            background-color: #f59e0b !important;
            color: #0f172a !important;
            font-weight: 700 !important;
        }
    </style>
</head>
<body class="bg-slate-100 font-sans antialiased">
    <!-- TOAST NOTIFICATION DYNAMIQUE -->
    <div id="toast" class="fixed top-5 right-5 z-50 transform translate-x-full transition-transform duration-300 ease-in-out bg-slate-900 text-white px-4 py-3 rounded-lg shadow-xl flex items-center gap-3">
        <i id="toastIcon" class="fa-solid fa-circle-check text-amber-500"></i>
        <span id="toastMsg" class="text-sm font-medium">Action effectuée</span>
    </div>

    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR INTERACTIVE -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col justify-between hidden md:flex">
            <div>
                <div class="p-5 text-center border-b border-slate-800 flex flex-col items-center">
                    <div class="w-16 h-16 bg-amber-500 rounded-xl flex items-center justify-center text-slate-900 shadow-lg mb-3">
                        <i class="fa-solid fa-shield-halved text-3xl"></i>
                    </div>
                    <h1 class="text-lg font-bold tracking-wider text-amber-500 uppercase">CHEPITIMAYAS SÉCURITÉ</h1>
                    <p class="text-xs text-slate-400">Gestion Agents & Matériels</p>
                </div>
                <nav class="mt-6 px-4 space-y-2">
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
            <div class="p-4 border-t border-slate-800 text-xs text-slate-500 text-center">
                © 2026 CHEPITIMAYAS SECURITE v2.5
            </div>
        </aside>

        <!-- CONTENU PRINCIPAL -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- HEADER -->
            <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <button class="md:hidden text-slate-700 text-xl"><i class="fa-solid fa-bars"></i></button>
                    <h2 id="pageTitle" class="text-xl font-bold text-slate-800">TABLEAU DE BORD DE SUIVI DES AGENTS</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- BOUTON SCANNER QR HEADER (ACTIVÉ) -->
                    <button onclick="ouvrirScannerQR()" class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold px-3.5 py-2 rounded-lg text-xs flex items-center gap-2 shadow transition">
                        <i class="fa-solid fa-qrcode text-sm"></i> Scanner Pointage
                    </button>
                    
                    <div id="statusBadgeContainer">
                        <!-- Statut BDD Dynamique -->
                    </div>
                    <div class="flex items-center space-x-3 border-l pl-4">
                        <div class="w-10 h-10 rounded-full bg-amber-500 text-slate-900 flex items-center justify-center font-bold shadow">
                            AM
                        </div>
                        <div class="hidden sm:block">
                            <p class="text-sm font-semibold text-slate-800">Admin MAYAS</p>
                            <p class="text-xs text-slate-500">Superviseur RH</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- VUES MULTIPLES -->
            <main class="p-6 space-y-6">

                <!-- SECTION 1 : DASHBOARD -->
                <div id="view-dashboard" class="tab-content space-y-6">
                    <!-- KPI CARDS -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
                            <p class="text-sm font-medium text-slate-500">Total Agents</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1" id="stat-total">0</p>
                        </div>
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
                            <p class="text-sm font-medium text-slate-500">Agents Actifs</p>
                            <p class="text-2xl font-bold text-emerald-600 mt-1" id="stat-actifs">0</p>
                        </div>
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
                            <p class="text-sm font-medium text-slate-500">Masse Salariale (Actifs)</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1" id="stat-masse">0 FCFA</p>
                        </div>
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
                            <p class="text-sm font-medium text-slate-500">Superviseurs</p>
                            <p class="text-2xl font-bold text-amber-600 mt-1" id="stat-superviseurs">0</p>
                        </div>
                    </div>

                    <!-- ACTIONS DE MASSE -->
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-wrap gap-3 justify-between items-center">
                        <div class="flex flex-wrap gap-2">
                            <button onclick="showToast('Fonctionnalité d\'ajout à connecter au backend', 'info')" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2.5 rounded-lg text-sm flex items-center gap-2 shadow transition">
                                <i class="fa-solid fa-user-plus"></i> Nouvel Agent
                            </button>
                            <!-- BOUTON SCANNER DE POINTAGE DANS LA BARRE PRINCIPALE -->
                            <button onclick="ouvrirModaleAjoutAgent()" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2.5 rounded-lg text-sm flex items-center gap-2 shadow transition">
    <i class="fa-solid fa-user-plus"></i> Nouvel Agent
</button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <select id="selectSiteExport" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-amber-500">
                                <option value="ALL">Tous les Sites</option>
                            </select>
                            <button onclick="exporterWave()" class="bg-sky-600 hover:bg-sky-700 text-white px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow transition">
                                <i class="fa-solid fa-mobile-screen-button"></i> Export Wave (.CSV)
                            </button>
                            <button onclick="exporterExcelComplet()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow transition">
                                <i class="fa-solid fa-file-excel"></i> Export Excel (.XLSX)
                            </button>
                        </div>
                    </div>

                    <!-- FILTRES MULTI-CRITÈRES DYNAMIQUES -->
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-wrap gap-4 items-center">
                        <div class="flex-1 min-w-[280px] relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400"></i>
                            <input type="text" id="searchInput" onkeyup="filtrerMultiCriteres()" placeholder="Recherche par nom, matricule, pièce, téléphone, rôle, site..." class="w-full border border-slate-300 rounded-lg pl-9 pr-8 py-2 text-sm focus:outline-none focus:border-amber-500">
                        </div>
                        <div>
                            <select id="filterRole" onchange="filtrerMultiCriteres()" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-amber-500">
                                <option value="">Tous les Rôles</option>
                                <option value="AGENT">Agents (Vigiles)</option>
                                <option value="SUPERVISEUR">Superviseurs</option>
                            </select>
                        </div>
                        <div>
                            <select id="filterStatut" onchange="filtrerMultiCriteres()" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-amber-500">
                                <option value="">Tous les Statuts</option>
                                <option value="Actif">Actif</option>
                                <option value="Inactif">Inactif</option>
                                <option value="Suspendu">Suspendu</option>
                                <option value="Rupture de contrat">Rupture de contrat</option>
                            </select>
                        </div>
                    </div>

                    <!-- TABLEAU PRINCIPAL DES AGENTS DYNAMIQUE -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                                    <th class="p-4">Matricule</th>
                                    <th class="p-4">Rôle</th>
                                    <th class="p-4">Nom & Prénoms</th>
                                    <th class="p-4">N° Pièce</th>
                                    <th class="p-4">N° Wave</th>
                                    <th class="p-4">Site</th>
                                    <th class="p-4">Salaire</th>
                                    <th class="p-4">Changement Statut</th>
                                    <th class="p-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="agentTableBody" class="divide-y divide-slate-100">
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SECTION 2 : GESTION AGENTS -->
                <div id="view-agents" class="tab-content hidden space-y-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex justify-between items-center flex-wrap gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Registre Global RH</h3>
                            <p class="text-xs text-slate-500">Consultez et administrez le personnel sous forme de fiches</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="text" id="searchCardsInput" onkeyup="filtrerCartesAgents()" placeholder="Rechercher une fiche..." class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-amber-500">
                        </div>
                    </div>
                    <div id="agentsGrid" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    </div>
                </div>

                <!-- SECTION 3 : SITES & CLIENTS -->
                <div id="view-sites" class="tab-content hidden space-y-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex justify-between items-center flex-wrap gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 mb-1">Sites & Contrats Clients</h3>
                            <p class="text-xs text-slate-500">Vue synthétique du personnel affecté par site</p>
                        </div>
                        <input type="text" id="searchSitesInput" onkeyup="filtrerSites()" placeholder="Rechercher un site..." class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-amber-500">
                    </div>
                    <div id="sitesList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    </div>
                </div>

                <!-- SECTION 4 : POSTES & GRILLE -->
                <div id="view-postes" class="tab-content hidden space-y-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800">Grille des Postes & Vacations</h3>
                        <p class="text-xs text-slate-500">Répartition des vacations Jour / Nuit par site</p>
                    </div>
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
                            <tbody id="grillePostesTableBody" class="divide-y divide-slate-100">
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SECTION 5 : PRÉ-PAIE & WAVE -->
                <div id="view-paie" class="tab-content hidden space-y-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex justify-between items-center flex-wrap gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Ordres de Virement - Wave Mobile Money</h3>
                            <p class="text-xs text-slate-500">Liste des paiements et ajustements pour les agents actifs</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="text" id="searchWaveInput" onkeyup="filtrerTableauWave()" placeholder="Rechercher dans Wave..." class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-amber-500">
                            <button onclick="exporterWave()" class="bg-sky-600 hover:bg-sky-700 text-white font-bold px-4 py-2 rounded-lg text-sm flex items-center gap-2 shadow">
                                <i class="fa-solid fa-download"></i> Télécharger CSV Wave
                            </button>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 border-b">
                                <tr>
                                    <th class="p-4">Matricule</th>
                                    <th class="p-4">Nom & Prénoms</th>
                                    <th class="p-4">N° Wave</th>
                                    <th class="p-4">Base + Primes</th>
                                    <th class="p-4 text-center">Ajuster Prime</th>
                                    <th class="p-4">Statut Virement</th>
                                </tr>
                            </thead>
                            <tbody id="waveTableBody" class="divide-y divide-slate-100">
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- MODALE : PROFIL DÉTAILLÉ & BADGE -->
    <div id="profileModal" class="fixed inset-0 bg-slate-900/60 hidden flex items-center justify-center p-4 z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-xl w-full overflow-hidden">
            <div class="bg-slate-900 p-6 text-white flex justify-between items-start relative">
                <div class="flex items-center space-x-4">
                    <img id="viewPhoto" src="" alt="Photo Profil" class="w-20 h-20 rounded-full border-4 border-amber-500 object-cover shadow-lg">
                    <div>
                        <h3 id="viewNomPrenom" class="text-xl font-bold text-white">-</h3>
                        <p id="viewRole" class="text-xs font-semibold text-amber-500 uppercase tracking-wider">-</p>
                        <p id="viewMatricule" class="text-xs text-slate-400 font-mono mt-0.5">-</p>
                    </div>
                </div>
                <button onclick="toggleProfileModal(false)" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>

            <div class="p-6 space-y-4 text-slate-700 text-sm">
                <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Site d'affectation</p>
                        <p id="viewSite" class="font-bold text-slate-800 mt-0.5">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Statut Contractuel</p>
                        <p id="viewStatut" class="font-bold text-emerald-600 mt-0.5">Actif</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Salaire Mensuel</p>
                        <p id="viewSalaire" class="font-bold text-slate-800 mt-0.5">0 FCFA</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Sexe</p>
                        <p id="viewSexe" class="font-bold text-slate-800 mt-0.5">Masculin</p>
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <div class="flex items-center space-x-3 text-slate-600">
                        <i class="fa-solid fa-id-card w-5 text-amber-500"></i>
                        <span>N° Pièce d'Identité : <strong id="viewPiece" class="text-slate-800 font-mono">-</strong></span>
                    </div>
                    <div class="flex items-center space-x-3 text-slate-600">
                        <i class="fa-solid fa-mobile-screen-button w-5 text-amber-500"></i>
                        <span>N° Paiement Wave : <strong id="viewWave" class="text-slate-800 font-mono">-</strong></span>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex flex-wrap gap-2 justify-between items-center">
                <div class="flex gap-2">
                    <button id="btnPrintModal" class="px-3 py-2 bg-amber-500 text-slate-900 font-bold rounded-lg text-xs hover:bg-amber-600 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-print"></i> Fiche PDF (A4)
                    </button>
                    <button id="btnPrintBadgeModal" class="px-3 py-2 bg-slate-900 text-amber-500 font-bold rounded-lg text-xs hover:bg-slate-800 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-id-badge"></i> Badge Provisoire
                    </button>
                </div>
                <button onclick="toggleProfileModal(false)" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg text-xs hover:bg-slate-300 transition">
                    Fermer
                </button>
            </div>
        </div>
    </div>

    <!-- MODALE : SCANNER DE POINTAGE QR CODE -->
    <div id="qrModal" class="fixed inset-0 bg-slate-900/80 hidden flex items-center justify-center p-4 z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
            <!-- Entête -->
            <div class="bg-slate-900 p-4 text-white flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-camera text-amber-500 text-lg"></i>
                    <h3 class="font-bold text-sm uppercase tracking-wider text-amber-500">Pointage Agents - Terrain</h3>
                </div>
                <button onclick="fermerScannerQR()" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>

            <!-- Zone Caméra & Lecteur -->
            <div class="p-6 space-y-4">
                <!-- Choix du type de pointage -->
                <div class="flex gap-2">
                    <label class="flex-1 border-2 border-amber-500 rounded-lg p-2 text-center cursor-pointer bg-amber-50">
                        <input type="radio" name="typePointage" value="ENTREE" checked class="hidden peer">
                        <span class="block text-xs font-bold text-slate-800"><i class="fa-solid fa-arrow-right-to-bracket text-emerald-600 mr-1"></i> Prise de Poste</span>
                    </label>
                    <label class="flex-1 border-2 border-slate-200 rounded-lg p-2 text-center cursor-pointer hover:border-amber-500">
                        <input type="radio" name="typePointage" value="SORTIE" class="hidden peer">
                        <span class="block text-xs font-bold text-slate-800"><i class="fa-solid fa-arrow-right-from-bracket text-red-600 mr-1"></i> Fin de Vacation</span>
                    </label>
                </div>

                <!-- Conteneur Flux Vidéo -->
                <div id="reader" class="w-full bg-slate-100 rounded-xl overflow-hidden border-2 border-dashed border-slate-300 min-h-[250px]"></div>

                <!-- Résultat du Scan -->
                <div id="scanResult" class="hidden p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 uppercase">Dernier Scan</span>
                        <span id="scanTime" class="text-xs font-mono font-bold text-amber-600">00:00:00</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                        <div>
                            <p id="agentNomResult" class="text-sm font-bold text-slate-800">-</p>
                            <p id="agentMatriculeResult" class="text-xs font-mono text-slate-500">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pied de Modale -->
            <div class="bg-slate-50 px-6 py-3 border-t border-slate-100 flex justify-end">
                <button onclick="fermerScannerQR()" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg text-xs hover:bg-slate-300 transition">
                    Fermer
                </button>
            </div>
        </div>
    </div>

    <!-- SCRIPT APPLICATION COMPLET -->
    <script>
        let globalAgents = [];
        let agentPrimes = {}; 
        let registredPointages = []; 
        let html5QrcodeScanner = null;
        const API_URL = 'get_agents.php';

        document.addEventListener('DOMContentLoaded', () => {
            chargerAgents();
        });

        // 1. CHARGEMENT DES DONNÉES
        async function chargerAgents() {
            afficherStatusBaseDeDonnees('chargement');
            try {
                const response = await fetch(API_URL);
                if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);
                
                const result = await response.json();
                if (result.success && Array.isArray(result.data)) {
                    globalAgents = result.data;
                    afficherStatusBaseDeDonnees('succes', `${globalAgents.length} agents`);
                    initialiserDashboard(globalAgents);
                } else {
                    throw new Error(result.message || 'Données invalides');
                }
            } catch (error) {
                console.warn("Connexion API backend indisponible, chargement du mode Démo :", error);
                afficherStatusBaseDeDonnees('erreur', 'Mode Démo local actif');
                
                globalAgents = [
                    { id: 1, matricule: 'CS-001', role: 'SUPERVISEUR', type_profil: 'SUPERVISEUR', nom_prenom: 'KOUASSI JEAN', numero_piece: 'CI00293849', numero_wave: '0701020304', site_nom: 'Site Plateau', vacation: 'Jour', salaire: 180000, statut: 'Actif', sexe: 'Masculin' },
                    { id: 2, matricule: 'CS-002', role: 'AGENT', type_profil: 'AGENT', nom_prenom: 'KONAN YAO', numero_piece: 'CI00384920', numero_wave: '0502030405', site_nom: 'Site Plateau', vacation: 'Nuit', salaire: 110000, statut: 'Actif', sexe: 'Masculin' },
                    { id: 3, matricule: 'CS-003', role: 'AGENT', type_profil: 'AGENT', nom_prenom: 'BAMBA SORY', numero_piece: 'CI00928374', numero_wave: '0708091011', site_nom: 'Site Cocody', vacation: 'Jour', salaire: 110000, statut: 'Actif', sexe: 'Masculin' },
                    { id: 4, matricule: 'CS-004', role: 'AGENT', type_profil: 'AGENT', nom_prenom: 'TURE AMINA', numero_piece: 'CI00112233', numero_wave: '0102030405', site_nom: 'Site Cocody', vacation: 'Nuit', salaire: 110000, statut: 'Suspendu', sexe: 'Féminin' }
                ];
                initialiserDashboard(globalAgents);
            }
        }

        function afficherStatusBaseDeDonnees(etat, message = '') {
            const container = document.getElementById('statusBadgeContainer');
            if (!container) return;
            if (etat === 'chargement') {
                container.innerHTML = `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200"><i class="fa-solid fa-spinner fa-spin"></i> Sync...</span>`;
            } else if (etat === 'succes') {
                container.innerHTML = `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200"><span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Connecté (${message})</span>`;
            } else {
                container.innerHTML = `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200"><i class="fa-solid fa-database"></i> ${message}</span>`;
            }
        }

        function initialiserDashboard(agents) {
            calculerEtAfficherKPI(agents);
            afficherTableauAgents(agents);
            afficherCartesAgents(agents);
            afficherSites(agents);
            afficherGrillePostes(agents);
            afficherTableauWave(agents);
            remplirSelecteurSites(agents);
        }

        // 2. BASCULEMENT D'ONGLETS
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('nav-item-active'));

            const targetView = document.getElementById(`view-${tabName}`);
            const targetNav = document.getElementById(`nav-${tabName}`);
            
            if (targetView) targetView.classList.remove('hidden');
            if (targetNav) targetNav.classList.add('nav-item-active');

            const titles = {
                'dashboard': 'Vue Synthétique / Dashboard',
                'agents': 'Registre Global du Personnel (Fiches RH)',
                'sites': 'Affectations & Clients par Site',
                'postes': 'Grille des Postes & Vacations (Jour / Nuit)',
                'paie': 'Gestion Pré-Paie & Virement Wave'
            };
            document.getElementById('pageTitle').innerText = titles[tabName] || 'Tableau de bord';
        }

        // 3. CALCUL KPI
        function calculerEtAfficherKPI(agents) {
            const total = agents.length;
            const actifs = agents.filter(a => a.statut === 'Actif');
            const masseSalariale = actifs.reduce((sum, a) => sum + (parseFloat(a.salaire) || 0), 0);
            const superviseurs = agents.filter(a => (a.type_profil || a.role || '').toUpperCase() === 'SUPERVISEUR').length;

            document.getElementById('stat-total').innerText = total;
            document.getElementById('stat-actifs').innerText = actifs.length;
            document.getElementById('stat-masse').innerText = masseSalariale.toLocaleString('fr-FR') + ' FCFA';
            document.getElementById('stat-superviseurs').innerText = superviseurs;
        }

        // 4. RECHERCHE MULTI-CRITÈRES
        function filtrerMultiCriteres() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const role = document.getElementById('filterRole').value;
            const statut = document.getElementById('filterStatut').value;

            const trouves = globalAgents.filter(a => {
                const matchQuery = (a.nom_prenom || '').toLowerCase().includes(query) ||
                                   (a.matricule || '').toLowerCase().includes(query) ||
                                   (a.numero_piece || '').toLowerCase().includes(query) ||
                                   (a.numero_wave || '').toLowerCase().includes(query) ||
                                   (a.site_nom || '').toLowerCase().includes(query);
                const matchRole = !role || (a.type_profil || a.role || '').toUpperCase() === role;
                const matchStatut = !statut || a.statut === statut;
                return matchQuery && matchRole && matchStatut;
            });

            afficherTableauAgents(trouves);
        }

        // 5. AFFICHAGE DES AGENTS DANS LE TABLEAU
        function afficherTableauAgents(agents) {
            const tbody = document.getElementById('agentTableBody');
            if (!tbody) return;

            if (agents.length === 0) {
                tbody.innerHTML = `<tr><td colspan="9" class="p-4 text-center text-slate-500">Aucun agent correspondant.</td></tr>`;
                return;
            }

            tbody.innerHTML = agents.map(a => `
                <tr class="hover:bg-slate-50 transition border-b border-slate-100">
                    <td class="p-4 font-mono font-bold text-amber-600">${a.matricule || '-'}</td>
                    <td class="p-4"><span class="text-xs px-2 py-1 bg-slate-100 font-semibold rounded">${a.type_profil || a.role || 'AGENT'}</span></td>
                    <td class="p-4 font-semibold text-slate-800">${a.nom_prenom || '-'}</td>
                    <td class="p-4 font-mono text-slate-600">${a.numero_piece || '-'}</td>
                    <td class="p-4 font-mono text-slate-600">${a.numero_wave || '-'}</td>
                    <td class="p-4">${a.site_nom || 'Non Affecté'}</td>
                    <td class="p-4 font-semibold text-slate-800">${parseFloat(a.salaire || 0).toLocaleString('fr-FR')} FCFA</td>
                    <td class="p-4">
                        <select onchange="changerStatutExpress(${a.id}, this.value)" class="text-xs font-bold px-2 py-1 rounded border border-slate-200 focus:outline-none">
                            <option value="Actif" ${a.statut === 'Actif' ? 'selected' : ''}>Actif</option>
                            <option value="Inactif" ${a.statut === 'Inactif' ? 'selected' : ''}>Inactif</option>
                            <option value="Suspendu" ${a.statut === 'Suspendu' ? 'selected' : ''}>Suspendu</option>
                            <option value="Rupture de contrat" ${a.statut === 'Rupture de contrat' ? 'selected' : ''}>Rupture</option>
                        </select>
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <button onclick="ouvrirFicheAgent(${a.id})" class="text-slate-600 hover:text-amber-500 p-1" title="Voir Fiche"><i class="fa-solid fa-eye"></i></button>
                        <button onclick="imprimerBadgePDF(${a.id})" class="text-slate-600 hover:text-slate-900 p-1" title="Imprimer Badge Provisoire"><i class="fa-solid fa-id-badge"></i></button>
                        <button onclick="imprimerFicheAgentPDF(${a.id})" class="text-slate-600 hover:text-red-500 p-1" title="Fiche PDF A4"><i class="fa-solid fa-file-pdf"></i></button>
                    </td>
                </tr>
            `).join('');
        }

        function afficherCartesAgents(agents) {
            const grid = document.getElementById('agentsGrid');
            if (!grid) return;

            grid.innerHTML = agents.map(a => `
                <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 flex flex-col justify-between space-y-4">
                    <div class="flex items-start gap-4">
                        <img src="${a.photo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(a.nom_prenom) + '&background=f59e0b&color=0f172a'}" class="w-14 h-14 rounded-full object-cover border-2 border-amber-500">
                        <div>
                            <h4 class="font-bold text-slate-800">${a.nom_prenom}</h4>
                            <p class="text-xs font-semibold text-amber-600">${a.type_profil || a.role || 'AGENT'}</p>
                            <p class="text-xs text-slate-400 font-mono">${a.matricule}</p>
                        </div>
                    </div>
                    <div class="text-xs space-y-1 text-slate-600 border-t pt-3">
                        <p><strong>Site:</strong> ${a.site_nom || 'Non assigné'}</p>
                        <p><strong>Statut:</strong> <span class="font-bold text-emerald-600">${a.statut}</span></p>
                    </div>
                    <button onclick="ouvrirFicheAgent(${a.id})" class="w-full bg-slate-900 text-white hover:bg-slate-800 text-xs py-2 rounded-lg font-medium transition">Consulter Profil</button>
                </div>
            `).join('');
        }

        function filtrerCartesAgents() {
            const query = document.getElementById('searchCardsInput').value.toLowerCase();
            const trouves = globalAgents.filter(a => 
                (a.nom_prenom || '').toLowerCase().includes(query) ||
                (a.matricule || '').toLowerCase().includes(query)
            );
            afficherCartesAgents(trouves);
        }

        function afficherSites(agents) {
            const container = document.getElementById('sitesList');
            if (!container) return;

            const sitesMap = {};
            agents.forEach(a => {
                const s = a.site_nom || 'Non Assignés';
                if (!sitesMap[s]) sitesMap[s] = [];
                sitesMap[s].push(a);
            });

            container.innerHTML = Object.keys(sitesMap).map(site => `
                <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 space-y-3">
                    <div class="flex justify-between items-center border-b pb-2">
                        <h4 class="font-bold text-slate-800"><i class="fa-solid fa-building text-amber-500 mr-2"></i>${site}</h4>
                        <span class="text-xs bg-slate-100 text-slate-700 font-bold px-2 py-1 rounded-full">${sitesMap[site].length} agents</span>
                    </div>
                    <ul class="text-xs space-y-1 divide-y divide-slate-50">
                        ${sitesMap[site].slice(0, 5).map(ag => `
                            <li class="py-1 flex justify-between">
                                <span>${ag.nom_prenom}</span>
                                <span class="font-mono text-slate-400">${ag.matricule}</span>
                            </li>
                        `).join('')}
                    </ul>
                </div>
            `).join('');
        }

        function filtrerSites() {
            const query = document.getElementById('searchSitesInput').value.toLowerCase();
            const trouves = globalAgents.filter(a => (a.site_nom || '').toLowerCase().includes(query));
            afficherSites(trouves);
        }

        function afficherGrillePostes(agents) {
            const tbody = document.getElementById('grillePostesTableBody');
            if (!tbody) return;

            const sitesMap = {};
            agents.forEach(a => {
                const s = a.site_nom || 'Non Assigné';
                if (!sitesMap[s]) sitesMap[s] = { jour: [], nuit: [] };
                if ((a.vacation || '').toLowerCase() === 'nuit') {
                    sitesMap[s].nuit.push(a);
                } else {
                    sitesMap[s].jour.push(a);
                }
            });

            tbody.innerHTML = Object.keys(sitesMap).map(site => `
                <tr class="border-b border-slate-100">
                    <td class="p-3 font-bold text-slate-800">${site}</td>
                    <td class="p-3 text-slate-600">${sitesMap[site].jour.map(a => a.nom_prenom).join(', ') || '<span class="text-slate-400">Aucun agent</span>'}</td>
                    <td class="p-3 text-slate-600">${sitesMap[site].nuit.map(a => a.nom_prenom).join(', ') || '<span class="text-slate-400">Aucun agent</span>'}</td>
                    <td class="p-3 text-center font-bold text-amber-600">${sitesMap[site].jour.length + sitesMap[site].nuit.length}</td>
                </tr>
            `).join('');
        }

        function afficherTableauWave(agents) {
            const tbody = document.getElementById('waveTableBody');
            if (!tbody) return;

            const actifs = agents.filter(a => a.statut === 'Actif');

            if (actifs.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-slate-500">Aucun agent actif pour le virement.</td></tr>`;
                return;
            }

            tbody.innerHTML = actifs.map(a => {
                const prime = agentPrimes[a.id] || 0;
                const total = (parseFloat(a.salaire) || 0) + prime;
                return `
                    <tr class="hover:bg-slate-50 border-b border-slate-100">
                        <td class="p-4 font-mono text-slate-600">${a.matricule}</td>
                        <td class="p-4 font-semibold text-slate-800">${a.nom_prenom}</td>
                        <td class="p-4 font-mono text-slate-600">${a.numero_wave || '-'}</td>
                        <td class="p-4 font-bold text-slate-900">${total.toLocaleString('fr-FR')} FCFA ${prime > 0 ? `<span class="text-xs text-emerald-600 font-normal">(+${prime.toLocaleString()} prime)</span>` : ''}</td>
                        <td class="p-4 text-center">
                            <button onclick="ajusterPrime(${a.id})" class="px-2 py-1 bg-amber-100 text-amber-800 hover:bg-amber-200 font-bold rounded text-xs">
                                <i class="fa-solid fa-pen-to-square"></i> Ajuster
                            </button>
                        </td>
                        <td class="p-4"><span class="px-2 py-1 bg-sky-100 text-sky-800 font-bold text-xs rounded-full">Prêt pour Wave</span></td>
                    </tr>
                `;
            }).join('');
        }

        function filtrerTableauWave() {
            const query = document.getElementById('searchWaveInput').value.toLowerCase();
            const trouves = globalAgents.filter(a => 
                (a.nom_prenom || '').toLowerCase().includes(query) ||
                (a.matricule || '').toLowerCase().includes(query) ||
                (a.numero_wave || '').toLowerCase().includes(query)
            );
            afficherTableauWave(trouves);
        }

        function ajusterPrime(id) {
            const agent = globalAgents.find(a => a.id == id);
            if (!agent) return;

            const primeActuelle = agentPrimes[id] || 0;
            const montant = prompt(`Saisir le montant des primes pour ${agent.nom_prenom} (FCFA) :`, primeActuelle);

            if (montant !== null) {
                const val = parseFloat(montant) || 0;
                agentPrimes[id] = val;
                showToast(`Prime de ${val.toLocaleString('fr-FR')} FCFA ajustée pour ${agent.nom_prenom}`);
                afficherTableauWave(globalAgents);
            }
        }

        function changerStatutExpress(id, nouveauStatut) {
            const agent = globalAgents.find(a => a.id == id);
            if (agent) {
                agent.statut = nouveauStatut;
                showToast(`Statut de ${agent.nom_prenom} mis à jour : ${nouveauStatut}`);
                calculerEtAfficherKPI(globalAgents);
                afficherTableauWave(globalAgents);
            }
        }

        // 6. MODALE PROFILE
        function ouvrirFicheAgent(id) {
            const agent = globalAgents.find(a => a.id == id);
            if (!agent) return;

            document.getElementById('viewNomPrenom').innerText = agent.nom_prenom || '-';
            document.getElementById('viewRole').innerText = agent.type_profil || agent.role || 'AGENT';
            document.getElementById('viewMatricule').innerText = agent.matricule || '-';
            document.getElementById('viewSite').innerText = agent.site_nom || 'Non Affecté';
            document.getElementById('viewStatut').innerText = agent.statut || 'Inconnu';
            document.getElementById('viewSalaire').innerText = parseFloat(agent.salaire || 0).toLocaleString('fr-FR') + ' FCFA';
            document.getElementById('viewSexe').innerText = agent.sexe || 'Masculin';
            document.getElementById('viewPiece').innerText = agent.numero_piece || '-';
            document.getElementById('viewWave').innerText = agent.numero_wave || '-';
            document.getElementById('viewPhoto').src = agent.photo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(agent.nom_prenom) + '&background=f59e0b&color=0f172a';

            document.getElementById('btnPrintModal').onclick = () => imprimerFicheAgentPDF(agent.id);
            document.getElementById('btnPrintBadgeModal').onclick = () => imprimerBadgePDF(agent.id);

            toggleProfileModal(true);
        }

        function toggleProfileModal(open) {
            const modal = document.getElementById('profileModal');
            if (modal) {
                if (open) modal.classList.remove('hidden');
                else modal.classList.add('hidden');
            }
        }

        function imprimerBadgePDF(agentId) {
            const agent = globalAgents.find(a => a.id == agentId);
            if (!agent) return;

            const element = document.createElement('div');
            element.style.width = '320px';
            element.style.padding = '15px';
            element.style.background = '#0f172a';
            element.style.color = '#ffffff';
            element.style.borderRadius = '12px';
            element.style.fontFamily = 'sans-serif';
            element.style.textAlign = 'center';

            element.innerHTML = `
                <div style="border: 2px solid #f59e0b; padding: 10px; border-radius: 8px;">
                    <h3 style="font-size: 12px; font-weight: bold; color: #f59e0b; margin: 0; text-transform: uppercase;">CHEPITIMAYAS SÉCURITÉ</h3>
                    <p style="font-size: 8px; color: #94a3b8; margin: 2px 0 10px 0;">BADGE PROVISOIRE D'AGENT</p>
                    <img src="${agent.photo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(agent.nom_prenom) + '&background=f59e0b&color=0f172a'}" style="width: 70px; height: 70px; border-radius: 50%; border: 2px solid #f59e0b; margin: 0 auto 8px auto; object-fit: cover;">
                    <h4 style="font-size: 13px; font-weight: bold; margin: 0; color: #fff;">${agent.nom_prenom}</h4>
                    <p style="font-size: 10px; font-weight: bold; color: #f59e0b; margin: 2px 0 6px 0;">${agent.type_profil || agent.role || 'AGENT'}</p>
                    <div style="background: #1e293b; padding: 6px; border-radius: 4px; font-size: 9px; text-align: left; margin-top: 8px;">
                        <div>Matricule : <strong>${agent.matricule}</strong></div>
                        <div>Pièce ID : <strong>${agent.numero_piece || '-'}</strong></div>
                        <div>Site : <strong>${agent.site_nom || 'Non Affecté'}</strong></div>
                    </div>
                </div>
            `;

            const opt = {
                margin: 5,
                filename: `badge_${agent.matricule}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: [85, 120], orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save();
        }

        function imprimerFicheAgentPDF(agentId) {
            const agent = globalAgents.find(a => a.id == agentId);
            if (!agent) return;

            const element = document.createElement('div');
            element.style.padding = '30px';
            element.style.fontFamily = 'sans-serif';

            element.innerHTML = `
                <div style="border-bottom: 2px solid #f59e0b; padding-bottom: 10px; margin-bottom: 20px;">
                    <h2 style="color: #0f172a; margin: 0;">CHEPITIMAYAS SÉCURITÉ</h2>
                    <p style="color: #64748b; margin: 0; font-size: 12px;">Fiche Individuelle d'Agent - RH</p>
                </div>
                <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <img src="${agent.photo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(agent.nom_prenom) + '&background=f59e0b&color=0f172a'}" style="width: 100px; height: 100px; border-radius: 8px; object-fit: cover;">
                    <div>
                        <h3 style="margin: 0; font-size: 18px;">${agent.nom_prenom}</h3>
                        <p style="margin: 5px 0; color: #f59e0b; font-weight: bold;">${agent.type_profil || agent.role || 'AGENT'}</p>
                        <p style="margin: 0; font-family: monospace;">Matricule : ${agent.matricule}</p>
                    </div>
                </div>
                <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                    <tr style="background: #f8fafc;"><td style="padding: 8px; border: 1px solid #e2e8f0;">Site d'affectation</td><td style="padding: 8px; border: 1px solid #e2e8f0; font-weight: bold;">${agent.site_nom || 'Non Assigné'}</td></tr>
                    <tr><td style="padding: 8px; border: 1px solid #e2e8f0;">Statut Contractuel</td><td style="padding: 8px; border: 1px solid #e2e8f0; font-weight: bold;">${agent.statut}</td></tr>
                    <tr style="background: #f8fafc;"><td style="padding: 8px; border: 1px solid #e2e8f0;">N° Pièce Identité</td><td style="padding: 8px; border: 1px solid #e2e8f0;">${agent.numero_piece || '-'}</td></tr>
                    <tr><td style="padding: 8px; border: 1px solid #e2e8f0;">N° Paiement Wave</td><td style="padding: 8px; border: 1px solid #e2e8f0;">${agent.numero_wave || '-'}</td></tr>
                    <tr style="background: #f8fafc;"><td style="padding: 8px; border: 1px solid #e2e8f0;">Salaire Mensuel Base</td><td style="padding: 8px; border: 1px solid #e2e8f0; font-weight: bold;">${parseFloat(agent.salaire || 0).toLocaleString('fr-FR')} FCFA</td></tr>
                </table>
            `;

            const opt = {
                margin: 10,
                filename: `fiche_${agent.matricule}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save();
        }

        // 7. EXPORTS
        function remplirSelecteurSites(agents) {
            const select = document.getElementById('selectSiteExport');
            if (!select) return;

            const sites = [...new Set(agents.map(a => a.site_nom).filter(Boolean))];
            select.innerHTML = `<option value="ALL">Tous les Sites</option>` + sites.map(s => `<option value="${s}">${s}</option>`).join('');
        }

        function exporterWave() {
            const siteFiltre = document.getElementById('selectSiteExport')?.value || 'ALL';
            const agentsConcertes = globalAgents.filter(a => a.statut === 'Actif' && (siteFiltre === 'ALL' || a.site_nom === siteFiltre));

            if (agentsConcertes.length === 0) {
                showToast('Aucun agent actif à exporter', 'warning');
                return;
            }

            let csvContent = "data:text/csv;charset=utf-8,Numero_Wave,Nom_Prenom,Montant,Matricule\n";
            agentsConcertes.forEach(a => {
                const prime = agentPrimes[a.id] || 0;
                const total = (parseFloat(a.salaire) || 0) + prime;
                csvContent += `"${a.numero_wave || ''}","${a.nom_prenom || ''}",${total},"${a.matricule || ''}"\n`;
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `virement_wave_${siteFiltre}_${new Date().toISOString().slice(0, 10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            showToast('Export Wave CSV généré !');
        }

        function exporterExcelComplet() {
            if (globalAgents.length === 0) {
                showToast('Aucune donnée à exporter', 'warning');
                return;
            }

            const dataToExport = globalAgents.map(a => ({
                Matricule: a.matricule,
                Role: a.type_profil || a.role || 'AGENT',
                'Nom & Prénoms': a.nom_prenom,
                'N° Pièce': a.numero_piece,
                'N° Wave': a.numero_wave,
                Site: a.site_nom || 'Non Assigné',
                Vacation: a.vacation || 'Jour',
                'Salaire (FCFA)': a.salaire,
                Statut: a.statut
            }));

            const worksheet = XLSX.utils.json_to_sheet(dataToExport);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, "Agents");
            XLSX.writeFile(workbook, `registre_agents_${new Date().toISOString().slice(0, 10)}.xlsx`);
            showToast('Export Excel généré !');
        }

        // 8. LOGIQUE DU SCANNER QR CODE (POINTAGE TERRAIN)
        function ouvrirScannerQR() {
            document.getElementById('qrModal').classList.remove('hidden');
            
            html5QrcodeScanner = new Html5Qrcode("reader");
            const config = { fps: 10, qrbox: { width: 200, height: 200 } };

            html5QrcodeScanner.start(
                { facingMode: "environment" }, 
                config,
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                console.error("Erreur d'accès caméra :", err);
                showToast("Impossible d'accéder à la caméra", "warning");
            });
        }

        function fermerScannerQR() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    html5QrcodeScanner.clear();
                    document.getElementById('qrModal').classList.add('hidden');
                }).catch(() => {
                    document.getElementById('qrModal').classList.add('hidden');
                });
            } else {
                document.getElementById('qrModal').classList.add('hidden');
            }
        }

        function onScanSuccess(decodedText) {
            const matriculeScanne = decodedText.trim();
            const agent = globalAgents.find(a => (a.matricule || '').toUpperCase() === matriculeScanne.toUpperCase());

            if (agent) {
                const typePointage = document.querySelector('input[name="typePointage"]:checked').value;
                const heures = new Date().toLocaleTimeString('fr-FR');
                const date = new Date().toLocaleDateString('fr-FR');

                registredPointages.push({
                    agent_id: agent.id,
                    matricule: agent.matricule,
                    nom_prenom: agent.nom_prenom,
                    site: agent.site_nom || 'Non Assigné',
                    type: typePointage,
                    heure: heures,
                    date: date
                });

                document.getElementById('scanResult').classList.remove('hidden');
                document.getElementById('scanTime').innerText = heures;
                document.getElementById('agentNomResult').innerText = agent.nom_prenom;
                document.getElementById('agentMatriculeResult').innerText = `${agent.matricule} • ${typePointage === 'ENTREE' ? 'Prise de poste' : 'Fin de vacation'}`;

                showToast(`Pointage ${typePointage} validé pour ${agent.nom_prenom}`);

                html5QrcodeScanner.pause(true);
                setTimeout(() => {
                    if (html5QrcodeScanner) html5QrcodeScanner.resume();
                }, 2500);

            } else {
                showToast(`Agent non trouvé pour le matricule : ${matriculeScanne}`, 'warning');
            }
        }

        function onScanFailure(error) {
            // Lecture continue silencieuse
        }

        // UTILITAIRES
        function showToast(msg, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toastMsg');
            const toastIcon = document.getElementById('toastIcon');

            if (!toast || !toastMsg) return;

            toastMsg.innerText = msg;
            if (type === 'warning') {
                toastIcon.className = "fa-solid fa-triangle-exclamation text-amber-500";
            } else if (type === 'info') {
                toastIcon.className = "fa-solid fa-circle-info text-sky-500";
            } else {
                toastIcon.className = "fa-solid fa-circle-check text-emerald-500";
            }

            toast.classList.remove('translate-x-full');
            setTimeout(() => {
                toast.classList.add('translate-x-full');
            }, 3000);
        }
    </script>
</body>
</html>