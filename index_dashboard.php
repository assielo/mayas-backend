<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGBD Web - CHEPITIMAYAS SÉCURITÉ</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- html2pdf.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<!-- html2pdf et ses dépendances explicites -->
<!-- html2pdf.js et ses dépendances explicites -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SheetJS (Excel) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
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
<body class="bg-gray-100 font-sans antialiased">

    <!-- Toast Notification -->
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
                        <i class="fa-solid fa-chart-line w-6"></i> Dashboard
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
                © 2026 CHEPITIMAYAS SECURITE v2.0
            </div>
        </aside>

        <!-- CONTENU PRINCIPAL -->
        <div class="flex-1 flex flex-col overflow-y-auto">

            <!-- HEADER -->
            <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <button class="md:hidden text-slate-700 text-xl"><i class="fa-solid fa-bars"></i></button>
                    <h2 id="pageTitle" class="text-xl font-bold text-slate-800">Vue Synthétique / Dashboard</h2>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <i class="fa-regular fa-bell text-slate-600 text-xl cursor-pointer"></i>
                        <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5">3</span>
                    </div>
                    <div class="flex items-center space-x-3 border-l pl-6">
                        <div class="w-10 h-10 rounded-full bg-amber-500 text-slate-900 flex items-center justify-center font-bold shadow">
                            AM
                        </div>
                        <div>
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

                    <!-- ACTIONS & EXPORTS -->
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-wrap gap-3 justify-between items-center">
                        <button onclick="toggleModal(true)" class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold px-4 py-2.5 rounded-lg text-sm flex items-center gap-2 shadow-md transition">
                            <i class="fa-solid fa-user-plus"></i> Nouvel Agent / Superviseur
                        </button>

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

                    <!-- RECHERCHE & FILTRES DASHBOARD -->
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-wrap gap-4 items-center">
                        <div class="flex-1 min-w-[280px] relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400"></i>
                            <input type="text" id="searchInput" onkeyup="filtrerTableau()" placeholder="Recherche par nom, matricule, pièce, téléphone, rôle, site..." class="w-full border border-slate-300 rounded-lg pl-9 pr-8 py-2 text-sm focus:outline-none focus:border-amber-500">
                        </div>
                        <div>
                            <select id="filterRole" onchange="filtrerTableau()" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-amber-500">
                                <option value="">Tous les Rôles</option>
                                <option value="AGENT">Agents (Vigiles)</option>
                                <option value="SUPERVISEUR">Superviseurs</option>
                            </select>
                        </div>
                        <div>
                            <select id="filterStatut" onchange="filtrerTableau()" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-amber-500">
                                <option value="">Tous les Statuts</option>
                                <option value="Actif">Actif</option>
                                <option value="Inactif">Inactif</option>
                                <option value="Suspendu">Suspendu</option>
                                <option value="Rupture de contrat">Rupture de contrat</option>
                            </select>
                        </div>
                    </div>

                    <!-- TABLEAU PRINCIPAL DES AGENTS -->
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
                                    <th class="p-4">Statut</th>
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
                            <button onclick="toggleModal(true)" class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold px-4 py-2 rounded-lg text-sm">
                                + Ajouter un Agent
                            </button>
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
                            <p class="text-xs text-slate-500">Liste des paiements pour les agents actifs</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="text" id="searchWaveInput" onkeyup="filtrerTableauWave()" placeholder="Rechercher dans Wave..." class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-amber-500">
                            <button onclick="exporterWave()" class="bg-sky-600 hover:bg-sky-700 text-white font-bold px-4 py-2 rounded-lg text-sm flex items-center gap-2 shadow">
                                <i class="fa-solid fa-download"></i> Télécharger CSV Wave
                            </button>
                            <button onclick="imprimerEtatPrePaiePDF()" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-lg text-sm flex items-center gap-2 shadow transition">
    <i class="fa-solid fa-file-pdf"></i> Télécharger PDF Pré-Paie
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
                                    <th class="p-4">Montant à Verser</th>
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

    <!-- MODALE : VISUALISATION PHOTO ET FICHE DÉTAILLÉE -->
    <div id="profileModal" class="fixed inset-0 bg-slate-900/60 hidden flex items-center justify-center p-4 z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-xl w-full overflow-hidden">
            <div class="bg-slate-900 p-6 text-white flex justify-between items-start relative">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <img id="viewPhoto" src="https://ui-avatars.com/api/?name=Agent&background=f59e0b&color=0f172a" alt="Photo Profil" class="w-20 h-20 rounded-full border-4 border-amber-500 object-cover shadow-lg">
                        <span id="viewStatusBadge" class="absolute bottom-0 right-0 w-4 h-4 rounded-full border-2 border-slate-900 bg-emerald-500"></span>
                    </div>
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

            <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end">
                <button onclick="toggleProfileModal(false)" class="px-5 py-2 bg-slate-900 text-white font-semibold rounded-lg text-sm hover:bg-slate-800 transition">
                    Fermer
                </button>
            </div>
        </div>
    </div>

    <!-- MODALE : FORMULAIRE D'AJOUT ET ÉDITION -->
    <div id="agentModal" class="fixed inset-0 bg-slate-900/50 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full p-6 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 id="modalFormTitle" class="text-lg font-bold text-slate-800">Ajouter un Agent / Superviseur</h3>
                <button onclick="toggleModal(false)" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>

            <form id="agentForm" onsubmit="sauvegarderAgent(event)" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" id="agent_id">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Type Profil *</label>
                    <select id="type_profil" required class="w-full border border-slate-300 rounded-lg p-2 text-sm bg-amber-50 font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
                        <option value="AGENT">Agent (Vigile)</option>
                        <option value="SUPERVISEUR">Superviseur</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Matricule *</label>
                    <input type="text" id="matricule" required placeholder="Ex: AGT-M-387" class="w-full border border-slate-300 rounded-lg p-2 text-sm font-mono font-bold text-amber-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Nom & Prénoms *</label>
                    <input type="text" id="nom_prenom" required placeholder="Ex: KOUASSI Kouame" class="w-full border border-slate-300 rounded-lg p-2 text-sm focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Sexe *</label>
                    <select id="sexe" required class="w-full border border-slate-300 rounded-lg p-2 text-sm focus:outline-none focus:border-amber-500">
                        <option value="M">Masculin (M)</option>
                        <option value="F">Féminin (F)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">N° Pièce (CNI/Passport)</label>
                    <input type="text" id="numero_piece" placeholder="Ex: C0012345678" class="w-full border border-slate-300 rounded-lg p-2 text-sm focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">N° Paiement Wave *</label>
                    <input type="text" id="numero_wave" required placeholder="Ex: 2250700000000" class="w-full border border-slate-300 rounded-lg p-2 text-sm focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Site d'affectation *</label>
                    <input type="text" id="site_nom" required placeholder="Ex: Siège Plateau" class="w-full border border-slate-300 rounded-lg p-2 text-sm focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Salaire (FCFA) *</label>
                    <input type="number" id="salaire" required placeholder="100000" class="w-full border border-slate-300 rounded-lg p-2 text-sm focus:outline-none focus:border-amber-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">URL Photo Profil (Optionnel)</label>
                    <input type="url" id="photo_url" placeholder="https://..." class="w-full border border-slate-300 rounded-lg p-2 text-sm focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Statut *</label>
                    <select id="statut" required class="w-full border border-slate-300 rounded-lg p-2 text-sm focus:outline-none focus:border-amber-500">
                        <option value="Actif">Actif</option>
                        <option value="Inactif">Inactif</option>
                        <option value="Suspendu">Suspendu</option>
                        <option value="Rupture de contrat">Rupture de contrat</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="toggleModal(false)" class="px-4 py-2 border rounded-lg text-sm text-slate-600 hover:bg-slate-50">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm hover:bg-slate-800 font-semibold shadow">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT APPLICATION -->
    <script>
        const API_URL = "http://127.0.0.1:8000/api/get_agents";
        let globalAgents = [];

        // Données fictives pour démonstration si l'API n'est pas disponible
        const mockAgents = [
            { id: 1, matricule: "AGT-M-001", role: "SUPERVISEUR", nom_prenom: "KOUASSI Kouame Assande", numero_piece: "C008473921", numero_wave: "2250701020304", site_nom: "Siège Plateau", salaire: 250000, statut: "Actif", sexe: "M", photo_url: "" },
            { id: 2, matricule: "AGT-M-002", role: "AGENT", nom_prenom: "KONAN Yao Patrice", numero_piece: "C001928374", numero_wave: "2250705060708", site_nom: "Port Autonome", salaire: 120000, statut: "Actif", sexe: "M", photo_url: "" },
            { id: 3, matricule: "AGT-M-003", role: "AGENT", nom_prenom: "YAPO Ahou Marie", numero_piece: "C009988776", numero_wave: "2250509101112", site_nom: "Zone Industrielle Yopougon", salaire: 110000, statut: "Inactif", sexe: "F", photo_url: "" },
            { id: 4, matricule: "AGT-M-004", role: "AGENT", nom_prenom: "DIABATE Souleymane", numero_piece: "C003344556", numero_wave: "2250713141516", site_nom: "Siège Plateau", salaire: 120000, statut: "Actif", sexe: "M", photo_url: "" }
        ];

        document.addEventListener('DOMContentLoaded', () => {
            chargerAgents();
        });

        async function chargerAgents() {
            try {
                const response = await fetch(API_URL);
                if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);
                const json = await response.json();
                if (json.success && Array.isArray(json.data)) {
                    globalAgents = json.data;
                } else {
                    globalAgents = mockAgents;
                }
            } catch (error) {
                console.warn("Connexion API échouée, utilisation des données de test:", error);
                globalAgents = mockAgents;
                afficherToast("Mode Démo (API non détectée)", true);
            }
            rafraichirToutesLesVues(globalAgents);
        }

        function rafraichirToutesLesVues(agents) {
            calculerKPIs(agents);
            afficherTableauGlobal(agents);
            afficherGrilleAgents(agents);
            afficherVueSites(agents);
            afficherGrillePostes(agents);
            afficherTableauWave(agents);
            remplirSelectSites(agents);
        }

        function switchTab(tabName) {
            const tabs = ['dashboard', 'agents', 'sites', 'postes', 'paie'];
            
            tabs.forEach(t => {
                const view = document.getElementById(`view-${t}`);
                const btn = document.getElementById(`nav-${t}`);
                if (view) view.classList.add('hidden');
                if (btn) btn.classList.remove('nav-item-active');
            });

            const activeView = document.getElementById(`view-${tabName}`);
            const activeBtn = document.getElementById(`nav-${tabName}`);
            if (activeView) activeView.classList.remove('hidden');
            if (activeBtn) activeBtn.classList.add('nav-item-active');

            const titles = {
                'dashboard': 'Vue Synthétique / Dashboard',
                'agents': 'Registre Global du Personnel RH',
                'sites': 'Affectation des Sites & Clients',
                'postes': 'Planification des Postes & Vacations',
                'paie': 'Ordres de Virement - Wave Mobile Money'
            };
            document.getElementById('pageTitle').textContent = titles[tabName] || 'Dashboard';
        }

        function calculerKPIs(agents) {
            const elTotal = document.getElementById('stat-total');
            const elActifs = document.getElementById('stat-actifs');
            const elMasse = document.getElementById('stat-masse');
            const elSupervisors = document.getElementById('stat-superviseurs');

            const actifs = agents.filter(a => a.statut && a.statut.trim().toLowerCase().includes('actif'));
            const superviseurs = agents.filter(a => a.role && a.role.toUpperCase().includes('SUPERVISEUR'));
            const masse = actifs.reduce((sum, a) => sum + parseFloat(a.salaire || 0), 0);

            if (elTotal) elTotal.textContent = agents.length;
            if (elActifs) elActifs.textContent = actifs.length;
            if (elMasse) elMasse.textContent = masse.toLocaleString('fr-FR') + ' FCFA';
            if (elSupervisors) elSupervisors.textContent = superviseurs.length;
        }

        function afficherTableauGlobal(agents) {
            const tbody = document.getElementById('agentTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';

            if (agents.length === 0) {
                tbody.innerHTML = `<tr><td colspan="9" class="p-4 text-center text-slate-400">Aucun agent trouvé</td></tr>`;
                return;
            }

            agents.forEach(agent => {
                const tr = document.createElement('tr');
                const statutPropre = agent.statut ? agent.statut.trim() : 'Inconnu';
                const isActif = statutPropre.toLowerCase().includes('actif');

                tr.innerHTML = `
                    <td class="p-4 font-mono font-bold text-amber-600">${agent.matricule || '-'}</td>
                    <td class="p-4"><span class="px-2 py-1 bg-slate-100 rounded text-xs font-semibold text-slate-700">${agent.role || 'AGENT'}</span></td>
                    <td class="p-4 font-semibold text-slate-800">${agent.nom_prenom || '-'}</td>
                    <td class="p-4 font-mono text-xs text-slate-500">${agent.numero_piece || '-'}</td>
                    <td class="p-4 font-mono text-xs text-slate-700">${agent.numero_wave || '-'}</td>
                    <td class="p-4">${agent.site_nom || 'Non assigné'}</td>
                    <td class="p-4 font-bold text-slate-900">${parseFloat(agent.salaire || 0).toLocaleString('fr-FR')} FCFA</td>
                    <td class="p-4"><span class="px-2.5 py-1 rounded-full text-xs font-bold ${isActif ? 'badge-actif' : 'badge-rupture'}">${statutPropre}</span></td>
                    <td class="p-4 text-center space-x-2">
                        <button onclick="voirProfil(${agent.id})" class="text-amber-500 hover:text-amber-600 p-1" title="Voir fiche"><i class="fa-solid fa-eye"></i></button>
                        <button onclick="supprimerAgent(${agent.id})" class="text-red-500 hover:text-red-600 p-1" title="Supprimer"><i class="fa-solid fa-trash"></i></button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function filtrerTableau() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const role = document.getElementById('filterRole').value.toLowerCase();
            const statut = document.getElementById('filterStatut').value.toLowerCase();

            const filtres = globalAgents.filter(a => {
                const matchQuery = !query || 
                    (a.nom_prenom && a.nom_prenom.toLowerCase().includes(query)) ||
                    (a.matricule && a.matricule.toLowerCase().includes(query)) ||
                    (a.numero_piece && a.numero_piece.toLowerCase().includes(query)) ||
                    (a.numero_wave && a.numero_wave.toLowerCase().includes(query)) ||
                    (a.site_nom && a.site_nom.toLowerCase().includes(query));

                const matchRole = !role || (a.role && a.role.toLowerCase() === role);
                const matchStatut = !statut || (a.statut && a.statut.toLowerCase() === statut);

                return matchQuery && matchRole && matchStatut;
            });

            afficherTableauGlobal(filtres);
        }

        function afficherGrilleAgents(agents) {
            const grid = document.getElementById('agentsGrid');
            if (!grid) return;
            grid.innerHTML = '';

            agents.forEach(agent => {
                const photo = agent.photo_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(agent.nom_prenom || 'Agent')}&background=f59e0b&color=0f172a`;
                const card = document.createElement('div');
                card.className = "bg-white p-5 rounded-xl shadow-sm border border-slate-200 flex items-center space-x-4";
                card.innerHTML = `
                    <img src="${photo}" class="w-14 h-14 rounded-full object-cover border-2 border-amber-500">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-800 truncate">${agent.nom_prenom || '-'}</p>
                        <p class="text-xs text-amber-600 font-mono font-semibold">${agent.matricule || '-'}</p>
                        <p class="text-xs text-slate-500 truncate"><i class="fa-solid fa-location-dot text-slate-400"></i> ${agent.site_nom || 'Non affecté'}</p>
                    </div>
                    <button onclick="voirProfil(${agent.id})" class="text-slate-400 hover:text-amber-500"><i class="fa-solid fa-chevron-right"></i></button>
                `;
                grid.appendChild(card);
            });
        }

        function filtrerCartesAgents() {
            const val = document.getElementById('searchCardsInput').value.toLowerCase();
            const filt = globalAgents.filter(a => 
                (a.nom_prenom && a.nom_prenom.toLowerCase().includes(val)) ||
                (a.matricule && a.matricule.toLowerCase().includes(val))
            );
            afficherGrilleAgents(filt);
        }

        function afficherVueSites(agents) {
            const container = document.getElementById('sitesList');
            if (!container) return;
            container.innerHTML = '';

            const sitesMap = {};
            agents.forEach(a => {
                const site = a.site_nom || 'Non assigné';
                if (!sitesMap[site]) sitesMap[site] = [];
                sitesMap[site].push(a);
            });

            Object.keys(sitesMap).forEach(site => {
                const list = sitesMap[site];
                const card = document.createElement('div');
                card.className = "bg-white p-5 rounded-xl shadow-sm border border-slate-200 space-y-3";
                card.innerHTML = `
                    <div class="flex justify-between items-center border-b pb-2">
                        <h4 class="font-bold text-slate-800"><i class="fa-solid fa-building text-amber-500 mr-2"></i>${site}</h4>
                        <span class="bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded-full font-bold">${list.length} agent(s)</span>
                    </div>
                    <ul class="text-xs text-slate-600 space-y-1 max-h-40 overflow-y-auto">
                        ${list.map(a => `<li class="flex justify-between"><span>${a.nom_prenom}</span> <strong class="font-mono text-slate-400">${a.matricule}</strong></li>`).join('')}
                    </ul>
                `;
                container.appendChild(card);
            });
        }

        function filtrerSites() {
            const query = document.getElementById('searchSitesInput').value.toLowerCase();
            const filtered = globalAgents.filter(a => (a.site_nom || 'Non assigné').toLowerCase().includes(query));
            afficherVueSites(filtered);
        }

        function afficherGrillePostes(agents) {
            const tbody = document.getElementById('grillePostesTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';

            const sitesMap = {};
            agents.forEach(a => {
                const site = a.site_nom || 'Non assigné';
                if (!sitesMap[site]) sitesMap[site] = [];
                sitesMap[site].push(a);
            });

            Object.keys(sitesMap).forEach((site, idx) => {
                const list = sitesMap[site];
                const jour = list.filter((_, i) => i % 2 === 0);
                const nuit = list.filter((_, i) => i % 2 !== 0);

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="p-3 font-bold text-slate-800">${site}</td>
                    <td class="p-3 text-xs text-slate-600">${jour.map(a => a.nom_prenom).join(', ') || 'Aucun'}</td>
                    <td class="p-3 text-xs text-slate-600">${nuit.map(a => a.nom_prenom).join(', ') || 'Aucun'}</td>
                    <td class="p-3 text-center font-bold text-slate-800">${list.length}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        function afficherTableauWave(agents) {
            const tbody = document.getElementById('waveTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';

            const actifs = agents.filter(a => a.statut && a.statut.trim().toLowerCase().includes('actif'));

            if (actifs.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="p-4 text-center text-slate-400">Aucun paiement actif à afficher</td></tr>`;
                return;
            }

            actifs.forEach(a => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="p-4 font-mono font-bold text-amber-600">${a.matricule || '-'}</td>
                    <td class="p-4 font-semibold text-slate-800">${a.nom_prenom || '-'}</td>
                    <td class="p-4 font-mono text-slate-700">${a.numero_wave || '-'}</td>
                    <td class="p-4 font-bold text-slate-900">${parseFloat(a.salaire || 0).toLocaleString('fr-FR')} FCFA</td>
                    <td class="p-4"><span class="px-2.5 py-1 rounded-full text-xs font-bold badge-actif">Prêt pour virement</span></td>
                `;
                tbody.appendChild(tr);
            });
        }

        function filtrerTableauWave() {
            const query = document.getElementById('searchWaveInput').value.toLowerCase();
            const filtered = globalAgents.filter(a => 
                (a.nom_prenom && a.nom_prenom.toLowerCase().includes(query)) ||
                (a.matricule && a.matricule.toLowerCase().includes(query)) ||
                (a.numero_wave && a.numero_wave.toLowerCase().includes(query))
            );
            afficherTableauWave(filtered);
        }

        function remplirSelectSites(agents) {
            const select = document.getElementById('selectSiteExport');
            if (!select) return;
            select.innerHTML = '<option value="ALL">Tous les Sites</option>';

            const sites = [...new Set(agents.map(a => a.site_nom).filter(Boolean))];
            sites.forEach(site => {
                const opt = document.createElement('option');
                opt.value = site;
                opt.textContent = site;
                select.appendChild(opt);
            });
        }

        function voirProfil(id) {
            const agent = globalAgents.find(a => a.id === id);
            if (!agent) return;

            document.getElementById('viewNomPrenom').textContent = agent.nom_prenom || '-';
            document.getElementById('viewRole').textContent = agent.role || 'AGENT';
            document.getElementById('viewMatricule').textContent = agent.matricule || '-';
            document.getElementById('viewSite').textContent = agent.site_nom || 'Non assigné';
            document.getElementById('viewStatut').textContent = agent.statut || 'Inconnu';
            document.getElementById('viewSalaire').textContent = parseFloat(agent.salaire || 0).toLocaleString('fr-FR') + ' FCFA';
            document.getElementById('viewSexe').textContent = agent.sexe === 'F' ? 'Féminin' : 'Masculin';
            document.getElementById('viewPiece').textContent = agent.numero_piece || '-';
            document.getElementById('viewWave').textContent = agent.numero_wave || '-';

            const photo = agent.photo_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(agent.nom_prenom || 'Agent')}&background=f59e0b&color=0f172a`;
            document.getElementById('viewPhoto').src = photo;

            toggleProfileModal(true);
        }

        function toggleProfileModal(show) {
            const modal = document.getElementById('profileModal');
            if (modal) modal.classList.toggle('hidden', !show);
        }

        function toggleModal(show) {
            const modal = document.getElementById('agentModal');
            if (modal) modal.classList.toggle('hidden', !show);
            if (!show) document.getElementById('agentForm').reset();
        }

        function sauvegarderAgent(e) {
            e.preventDefault();
            const id = document.getElementById('agent_id').value;
            const newAgent = {
                id: id ? parseInt(id) : Date.now(),
                role: document.getElementById('type_profil').value,
                matricule: document.getElementById('matricule').value,
                nom_prenom: document.getElementById('nom_prenom').value,
                sexe: document.getElementById('sexe').value,
                numero_piece: document.getElementById('numero_piece').value,
                numero_wave: document.getElementById('numero_wave').value,
                site_nom: document.getElementById('site_nom').value,
                salaire: parseFloat(document.getElementById('salaire').value || 0),
                photo_url: document.getElementById('photo_url').value,
                statut: document.getElementById('statut').value
            };

            if (id) {
                const idx = globalAgents.findIndex(a => a.id === parseInt(id));
                if (idx !== -1) globalAgents[idx] = newAgent;
            } else {
                globalAgents.unshift(newAgent);
            }

            rafraichirToutesLesVues(globalAgents);
            toggleModal(false);
            afficherToast("Agent enregistré avec succès", true);
        }

        function supprimerAgent(id) {
            if (confirm("Voulez-vous vraiment supprimer cet agent ?")) {
                globalAgents = globalAgents.filter(a => a.id !== id);
                rafraichirToutesLesVues(globalAgents);
                afficherToast("Agent supprimé", true);
            }
        }

        function exporterWave() {
            const site = document.getElementById('selectSiteExport').value;
            const agentsFiltres = site === 'ALL' ? globalAgents : globalAgents.filter(a => a.site_nom === site);
            const actifs = agentsFiltres.filter(a => a.statut && a.statut.trim().toLowerCase().includes('actif'));

            let csv = "Matricule,Nom,Numero Wave,Montant\n";
            actifs.forEach(a => {
                csv += `"${a.matricule}","${a.nom_prenom}","${a.numero_wave}",${a.salaire}\n`;
            });

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = `Paiement_Wave_${site}_${new Date().toISOString().slice(0,10)}.csv`;
            link.click();
        }

        function exporterExcelComplet() {
            const ws = XLSX.utils.json_to_sheet(globalAgents);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Agents");
            XLSX.writeFile(wb, `Registre_RH_CHEPITIMAYAS_${new Date().toISOString().slice(0,10)}.xlsx`);
        }

        function afficherToast(message, success = true) {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toastMsg');
            const toastIcon = document.getElementById('toastIcon');

            if (!toast) return;

            toastMsg.textContent = message;
            toastIcon.className = success ? "fa-solid fa-circle-check text-amber-500" : "fa-solid fa-circle-xmark text-red-500";
            
            toast.classList.remove('translate-x-full');
            setTimeout(() => {
                toast.classList.add('translate-x-full');
            }, 3000);
        }
        async function genererFichePDF() {
    const element = document.getElementById('pdfContent');
    if (!element) {
        alert("Erreur : Élément #pdfContent introuvable.");
        return;
    }

    // Récupération des données pour le nom du fichier
    const matricule = document.getElementById('viewMatricule')?.textContent.trim() || 'AGENT';
    const nomAgent = document.getElementById('viewNomPrenom')?.textContent.trim().replace(/\s+/g, '_') || 'Fiche';

    const opt = {
        margin:       [10, 10, 10, 10],
        filename:     `Fiche_${matricule}_${nomAgent}.pdf`,
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { 
            scale: 2, 
            useCORS: true, 
            allowTaint: true,
            scrollX: 0,
            scrollY: 0
        },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    try {
        // Force le rendu en générant le PDF via le worker html2pdf
        await html2pdf().set(opt).from(element).save();
    } catch (err) {
        console.error("Détail de l'erreur PDF :", err);
        alert("Erreur lors de la génération : " + err.message);
    }
}
// Variable globale pour stocker l'agent sélectionné dans la modale
let currentAgentProfile = null;

// Surcharger la fonction d'affichage de la modale de profil pour enregistrer l'agent
function ouvrirModalProfil(agent) {
    currentAgentProfile = agent;
    document.getElementById('viewNomPrenom').textContent = agent.nom_prenom;
    document.getElementById('viewRole').textContent = agent.role;
    document.getElementById('viewMatricule').textContent = agent.matricule;
    document.getElementById('viewSite').textContent = agent.site_nom || '-';
    document.getElementById('viewStatut').textContent = agent.statut || 'Actif';
    document.getElementById('viewSalaire').textContent = (parseFloat(agent.salaire) || 0).toLocaleString('fr-FR') + ' FCFA';
    document.getElementById('viewSexe').textContent = agent.sexe === 'F' ? 'Féminin' : 'Masculin';
    document.getElementById('viewPiece').textContent = agent.numero_piece || 'N/A';
    document.getElementById('viewWave').textContent = agent.numero_wave || 'N/A';
    
    // Photo par défaut
    const photoImg = document.getElementById('viewPhoto');
    if (photoImg) {
        photoImg.src = agent.photo_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(agent.nom_prenom)}&background=f59e0b&color=0f172a`;
    }

    toggleProfileModal(true);
}

function toggleProfileModal(show) {
    const modal = document.getElementById('profileModal');
    if (modal) {
        if (show) modal.classList.remove('hidden');
        else modal.classList.add('hidden');
    }
}

// -------------------------------------------------------------
// 1. MODULE D'IMPRESSION : FICHE INDIVIDUELLE AGENT
// -------------------------------------------------------------
async function imprimerFicheAgentActuel() {
    if (!currentAgentProfile) {
        alert("Aucun agent sélectionné.");
        return;
    }

    const a = currentAgentProfile;
    const printArea = document.getElementById('pdfPrintTemplate');

    // Construction du document HTML propre pour la fiche agent
    printArea.innerHTML = `
        <div style="border: 2px solid #0f172a; border-radius: 12px; padding: 24px; background: #fff;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f59e0b; padding-bottom: 15px; margin-bottom: 20px;">
                <div>
                    <h1 style="margin: 0; font-size: 22px; color: #0f172a; font-weight: bold; text-transform: uppercase;">CHEPITIMAYAS SÉCURITÉ</h1>
                    <p style="margin: 3px 0 0 0; font-size: 12px; color: #64748b;">Services de Sécurité & Management des Effectifs</p>
                </div>
                <div style="text-align: right;">
                    <span style="background: #0f172a; color: #f59e0b; padding: 6px 12px; border-radius: 6px; font-weight: bold; font-size: 12px; font-family: monospace;">
                        FICHE SIGNALÉTIQUE
                    </span>
                    <p style="margin: 5px 0 0 0; font-size: 10px; color: #94a3b8;">Généré le : ${new Date().toLocaleDateString('fr-FR')}</p>
                </div>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 70%;">
                            <h2 style="margin: 0; font-size: 18px; color: #0f172a;">${a.nom_prenom}</h2>
                            <p style="margin: 4px 0; font-size: 13px; color: #d97706; font-weight: bold;">RÔLE : ${a.role}</p>
                            <p style="margin: 0; font-size: 12px; font-family: monospace; color: #475569;">MATRICULE : <strong>${a.matricule}</strong></p>
                        </td>
                        <td style="width: 30%; text-align: right;">
                            <span style="display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: bold; 
                                ${a.statut === 'Actif' ? 'background: #def7ec; color: #03543f;' : 'background: #fde8e8; color: #9b1c1c;'}">
                                ${a.statut || 'Actif'}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <h3 style="font-size: 14px; text-transform: uppercase; color: #0f172a; border-left: 4px solid #f59e0b; padding-left: 8px; margin-bottom: 12px;">Information Contractuelle & Affectation</h3>
            
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 13px;">
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 10px; font-weight: bold; color: #64748b; width: 40%;">Site d'affectation principal</td>
                    <td style="padding: 10px; font-weight: bold; color: #0f172a;">${a.site_nom || 'Non assigné'}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0; background: #fafafa;">
                    <td style="padding: 10px; font-weight: bold; color: #64748b;">N° Pièce d'Identité (CNI/Passport)</td>
                    <td style="padding: 10px; font-family: monospace; font-weight: bold;">${a.numero_piece || 'N/A'}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 10px; font-weight: bold; color: #64748b;">N° Compte Virement Wave</td>
                    <td style="padding: 10px; font-family: monospace; font-weight: bold; color: #0284c7;">${a.numero_wave || 'N/A'}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0; background: #fafafa;">
                    <td style="padding: 10px; font-weight: bold; color: #64748b;">Salaire de base Mensuel</td>
                    <td style="padding: 10px; font-weight: bold; color: #059669;">${(parseFloat(a.salaire) || 0).toLocaleString('fr-FR')} FCFA</td>
                </tr>
                <tr>
                    <td style="padding: 10px; font-weight: bold; color: #64748b;">Genre / Sexe</td>
                    <td style="padding: 10px;">${a.sexe === 'F' ? 'Féminin (F)' : 'Masculin (M)'}</td>
                </tr>
            </table>

            <div style="margin-top: 40px; display: flex; justify-content: space-between; text-align: center; font-size: 12px; color: #475569;">
                <div style="width: 40%; border-top: 1px dashed #94a3b8; padding-top: 8px;">
                    Signature de l'Agent
                </div>
                <div style="width: 40%; border-top: 1px dashed #94a3b8; padding-top: 8px;">
                    Direction des Ressources Humaines
                </div>
            </div>
        </div>
    `;

    // Configuration PDF
    const opt = {
        margin:       [10, 10, 10, 10],
        filename:     `Fiche_Agent_${a.matricule}.pdf`,
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    try {
        await html2pdf().set(opt).from(printArea).save();
    } catch (err) {
        console.error("Erreur PDF Fiche Agent :", err);
        alert("Impossible de générer le PDF : " + err.message);
    }
}

// -------------------------------------------------------------
// 2. MODULE D'IMPRESSION : ÉTAT DE PRÉ-PAIE GLOBAL
// -------------------------------------------------------------
async function imprimerEtatPrePaiePDF() {
    const agentsActifs = globalAgents.filter(a => a.statut && a.statut.trim().toLowerCase().includes('actif'));
    if (agentsActifs.length === 0) {
        alert("Aucun agent actif disponible pour l'état de pré-paie.");
        return;
    }

    const printArea = document.getElementById('pdfPrintTemplate');
    const totalMasse = agentsActifs.reduce((sum, a) => sum + parseFloat(a.salaire || 0), 0);

    // Génération du tableau de pré-paie
    let rowsHtml = agentsActifs.map((a, index) => `
        <tr style="border-bottom: 1px solid #e2e8f0; ${index % 2 === 1 ? 'background-color: #f8fafc;' : ''}">
            <td style="padding: 8px; font-family: monospace; font-size: 11px;">${a.matricule}</td>
            <td style="padding: 8px; font-weight: 600; font-size: 12px;">${a.nom_prenom}</td>
            <td style="padding: 8px; font-size: 11px;">${a.site_nom || 'N/A'}</td>
            <td style="padding: 8px; font-family: monospace; font-size: 11px; color: #0284c7;">${a.numero_wave || 'N/A'}</td>
            <td style="padding: 8px; text-align: right; font-weight: bold; font-size: 12px; color: #0f172a;">${(parseFloat(a.salaire) || 0).toLocaleString('fr-FR')} FCFA</td>
        </tr>
    `).join('');

    printArea.innerHTML = `
        <div style="background: #fff; padding: 10px;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #0f172a; padding-bottom: 10px; margin-bottom: 15px;">
                <div>
                    <h1 style="margin: 0; font-size: 20px; color: #0f172a; font-weight: bold;">CHEPITIMAYAS SÉCURITÉ</h1>
                    <p style="margin: 2px 0 0 0; font-size: 12px; color: #64748b;">État Général Récapitulatif de Pré-Paie (Agents Actifs)</p>
                </div>
                <div style="text-align: right;">
                    <p style="margin: 0; font-size: 11px; font-weight: bold; color: #d97706;">PÉRIODE : ${new Date().toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' }).toUpperCase()}</p>
                    <p style="margin: 2px 0 0 0; font-size: 10px; color: #94a3b8;">Effectif Actif : ${agentsActifs.length} Agents</p>
                </div>
            </div>

            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; text-align: left;">
                <thead>
                    <tr style="background: #0f172a; color: #ffffff; font-size: 11px; text-transform: uppercase;">
                        <th style="padding: 8px;">Matricule</th>
                        <th style="padding: 8px;">Nom & Prénoms</th>
                        <th style="padding: 8px;">Site Affecté</th>
                        <th style="padding: 8px;">N° Wave</th>
                        <th style="padding: 8px; text-align: right;">Montant Net</th>
                    </tr>
                </thead>
                <tbody>
                    ${rowsHtml}
                </tbody>
            </table>

            <div style="display: flex; justify-content: flex-end; margin-top: 15px;">
                <table style="width: 50%; border-collapse: collapse;">
                    <tr style="background: #f59e0b; color: #0f172a;">
                        <td style="padding: 10px; font-weight: bold; font-size: 13px;">MASSE SALARIALE TOTAL :</td>
                        <td style="padding: 10px; text-align: right; font-weight: bold; font-size: 14px;">${totalMasse.toLocaleString('fr-FR')} FCFA</td>
                    </tr>
                </table>
            </div>
        </div>
    `;

    const opt = {
        margin:       [8, 8, 8, 8],
        filename:     `Etat_PrePaie_${new Date().toISOString().slice(0,10)}.pdf`,
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    try {
        await html2pdf().set(opt).from(printArea).save();
    } catch (err) {
        console.error("Erreur PDF Pré-Paie :", err);
        alert("Erreur lors de la génération du document : " + err.message);
    }
}
// =============================================================
// 1. GESTION DES EXPEDITIONS ET EXPORTS (EXCEL ET WAVE)
// =============================================================

// Export des ordres de virement au format CSV compatible Wave Mobile Money
function exporterWave() {
    const siteFiltre = document.getElementById('selectSiteExport')?.value || 'ALL';
    let agentsActifs = globalAgents.filter(a => a.statut && a.statut.trim().toLowerCase().includes('actif'));

    if (siteFiltre !== 'ALL') {
        agentsActifs = agentsActifs.filter(a => a.site_nom === siteFiltre);
    }

    if (agentsActifs.length === 0) {
        afficherToast("Aucun agent actif trouvé pour l'export Wave", true);
        return;
    }

    // Formatage CSV selon le standard Wave : Numéro, Montant, Nom
    let csvContent = "data:text/csv;charset=utf-8,Numero_Wave,Montant_FCFA,Nom_Prenoms,Matricule\n";
    agentsActifs.forEach(a => {
        const numWave = a.numero_wave ? a.numero_wave.replace(/\s+/g, '') : '';
        const nomPropre = `"${a.nom_prenom.replace(/"/g, '""')}"`;
        csvContent += `${numWave},${a.salaire || 0},${nomPropre},${a.matricule}\n`;
    });

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `Virements_Wave_${siteFiltre}_${new Date().toISOString().slice(0,10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    afficherToast(`Export Wave généré pour ${agentsActifs.length} agent(s)`);
}

// Export global au format Excel (.xlsx) via SheetJS
function exporterExcelComplet() {
    if (!globalAgents || globalAgents.length === 0) {
        afficherToast("Aucune donnée à exporter", true);
        return;
    }

    // Préparation des données propres
    const dataFormatted = globalAgents.map(a => ({
        "Matricule": a.matricule,
        "Rôle": a.role,
        "Nom & Prénoms": a.nom_prenom,
        "Sexe": a.sexe,
        "N° Pièce": a.numero_piece || 'N/A',
        "N° Wave": a.numero_wave || 'N/A',
        "Site d'affectation": a.site_nom || 'N/A',
        "Salaire (FCFA)": a.salaire,
        "Statut": a.statut
    }));

    const worksheet = XLSX.utils.json_to_sheet(dataFormatted);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Registre Agents");

    XLSX.writeFile(workbook, `Registre_Agents_CHEPITIMAYAS_${new Date().toISOString().slice(0,10)}.xlsx`);
    afficherToast("Fichier Excel généré avec succès");
}


// =============================================================
// 2. RENDU DES VUES (TABLEAUX ET GRILLES)
// =============================================================

function afficherTableauGlobal(agents) {
    const tbody = document.getElementById('agentTableBody');
    if (!tbody) return;

    if (agents.length === 0) {
        tbody.innerHTML = `<tr><td colspan="9" class="text-center py-6 text-slate-400">Aucun agent trouvé.</td></tr>`;
        return;
    }

    tbody.innerHTML = agents.map(a => `
        <tr class="hover:bg-slate-50 transition">
            <td class="p-4 font-mono font-bold text-amber-600">${a.matricule}</td>
            <td class="p-4"><span class="px-2 py-1 rounded text-xs font-bold ${a.role === 'SUPERVISEUR' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700'}">${a.role}</span></td>
            <td class="p-4 font-medium text-slate-800">${a.nom_prenom}</td>
            <td class="p-4 font-mono text-xs text-slate-600">${a.numero_piece || '-'}</td>
            <td class="p-4 font-mono text-xs text-sky-700 font-bold">${a.numero_wave || '-'}</td>
            <td class="p-4 text-slate-700">${a.site_nom || '-'}</td>
            <td class="p-4 font-bold text-slate-900">${(parseFloat(a.salaire) || 0).toLocaleString('fr-FR')} FCFA</td>
            <td class="p-4">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold ${getStatutBadgeClass(a.statut)}">
                    ${a.statut || 'Actif'}
                </span>
            </td>
            <td class="p-4 text-center space-x-2">
                <button onclick='ouvrirModalProfil(${JSON.stringify(a).replace(/'/g, "&apos;")})' class="text-slate-500 hover:text-amber-500 p-1" title="Voir Fiche">
                    <i class="fa-solid fa-eye"></i>
                </button>
                <button onclick='editerAgent(${JSON.stringify(a).replace(/'/g, "&apos;")})' class="text-slate-500 hover:text-blue-600 p-1" title="Modifier">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function afficherGrilleAgents(agents) {
    const container = document.getElementById('agentsGrid');
    if (!container) return;

    container.innerHTML = agents.map(a => `
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between space-y-4">
            <div class="flex items-center space-x-4">
                <img src="${a.photo_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(a.nom_prenom)}&background=f59e0b&color=0f172a`}" class="w-14 h-14 rounded-full object-cover border-2 border-amber-500">
                <div>
                    <h4 class="font-bold text-slate-800 text-sm leading-snug">${a.nom_prenom}</h4>
                    <p class="text-xs text-amber-600 font-semibold uppercase">${a.role}</p>
                    <p class="text-xs font-mono text-slate-400">${a.matricule}</p>
                </div>
            </div>
            <div class="text-xs space-y-1.5 pt-2 border-t border-slate-100 text-slate-600">
                <p><i class="fa-solid fa-building w-4 text-slate-400"></i> Site : <strong>${a.site_nom || 'Non affecté'}</strong></p>
                <p><i class="fa-solid fa-mobile-screen w-4 text-slate-400"></i> Wave : <strong class="font-mono text-sky-700">${a.numero_wave || '-'}</strong></p>
            </div>
            <button onclick='ouvrirModalProfil(${JSON.stringify(a).replace(/'/g, "&apos;")})' class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs rounded-lg transition">
                Consulter la Fiche RH
            </button>
        </div>
    `).join('');
}

function afficherVueSites(agents) {
    const container = document.getElementById('sitesList');
    if (!container) return;

    // Regroupement des agents par site
    const sitesMap = {};
    agents.forEach(a => {
        const site = a.site_nom || 'Sans Affectation';
        if (!sitesMap[site]) sitesMap[site] = [];
        sitesMap[site].push(a);
    });

    container.innerHTML = Object.keys(sitesMap).map(site => {
        const liste = sitesMap[site];
        const actifs = liste.filter(a => a.statut && a.statut.toLowerCase().includes('actif')).length;

        return `
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-3">
                <div class="flex justify-between items-start border-b pb-3">
                    <div>
                        <h4 class="font-bold text-slate-900">${site}</h4>
                        <p class="text-xs text-slate-500">${liste.length} Agent(s) assigné(s)</p>
                    </div>
                    <span class="bg-emerald-100 text-emerald-800 font-bold text-xs px-2.5 py-1 rounded-full">${actifs} Actif(s)</span>
                </div>
                <div class="space-y-2 max-h-40 overflow-y-auto text-xs">
                    ${liste.map(a => `
                        <div class="flex justify-between items-center p-2 bg-slate-50 rounded">
                            <span class="font-medium text-slate-700">${a.nom_prenom}</span>
                            <span class="font-mono text-slate-400 text-[10px]">${a.matricule}</span>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }).join('');
}

function afficherGrillePostes(agents) {
    const tbody = document.getElementById('grillePostesTableBody');
    if (!tbody) return;

    const sites = [...new Set(agents.map(a => a.site_nom || 'Non Affecté'))];

    tbody.innerHTML = sites.map(site => {
        const total = agents.filter(a => (a.site_nom || 'Non Affecté') === site).length;
        // Simulation de la répartition jour/nuit (50/50 par défaut)
        const jour = Math.ceil(total / 2);
        const nuit = Math.floor(total / 2);

        return `
            <tr class="hover:bg-slate-50 border-b">
                <td class="p-3 font-bold text-slate-800">${site}</td>
                <td class="p-3 text-amber-700 font-semibold"><i class="fa-solid fa-sun mr-1"></i> ${jour} Agent(s)</td>
                <td class="p-3 text-indigo-700 font-semibold"><i class="fa-solid fa-moon mr-1"></i> ${nuit} Agent(s)</td>
                <td class="p-3 text-center font-bold bg-slate-50 text-slate-900">${total}</td>
            </tr>
        `;
    }).join('');
}

function afficherTableauWave(agents) {
    const tbody = document.getElementById('waveTableBody');
    if (!tbody) return;

    const actifs = agents.filter(a => a.statut && a.statut.trim().toLowerCase().includes('actif'));

    tbody.innerHTML = actifs.map(a => `
        <tr class="hover:bg-slate-50 border-b">
            <td class="p-4 font-mono font-bold text-slate-600">${a.matricule}</td>
            <td class="p-4 font-semibold text-slate-800">${a.nom_prenom}</td>
            <td class="p-4 font-mono font-bold text-sky-600">${a.numero_wave || 'N/A'}</td>
            <td class="p-4 font-bold text-emerald-600">${(parseFloat(a.salaire) || 0).toLocaleString('fr-FR')} FCFA</td>
            <td class="p-4"><span class="bg-sky-100 text-sky-800 text-xs font-bold px-2.5 py-1 rounded-full"><i class="fa-solid fa-circle-check"></i> Prêt pour Wave</span></td>
        </tr>
    `).join('');
}

function remplirSelectSites(agents) {
    const select = document.getElementById('selectSiteExport');
    if (!select) return;

    const sites = [...new Set(agents.map(a => a.site_nom).filter(Boolean))];
    select.innerHTML = `<option value="ALL">Tous les Sites</option>` + sites.map(s => `<option value="${s}">${s}</option>`).join('');
}


// =============================================================
// 3. FORMULAIRE & ACTIONS UTILISATEUR
// =============================================================

function toggleModal(show) {
    const modal = document.getElementById('agentModal');
    if (modal) {
        if (show) modal.classList.remove('hidden');
        else {
            modal.classList.add('hidden');
            document.getElementById('agentForm').reset();
            document.getElementById('agent_id').value = '';
        }
    }
}

function sauvegarderAgent(event) {
    event.preventDefault();

    const id = document.getElementById('agent_id').value;
    const nouvelAgent = {
        id: id ? parseInt(id) : Date.now(),
        role: document.getElementById('type_profil').value,
        matricule: document.getElementById('matricule').value.trim(),
        nom_prenom: document.getElementById('nom_prenom').value.trim(),
        sexe: document.getElementById('sexe').value,
        numero_piece: document.getElementById('numero_piece').value.trim(),
        numero_wave: document.getElementById('numero_wave').value.trim(),
        site_nom: document.getElementById('site_nom').value.trim(),
        salaire: parseFloat(document.getElementById('salaire').value) || 0,
        photo_url: document.getElementById('photo_url').value.trim(),
        statut: document.getElementById('statut').value
    };

    if (id) {
        // Modification
        const index = globalAgents.findIndex(a => a.id == id);
        if (index !== -1) globalAgents[index] = nouvelAgent;
        afficherToast("Agent mis à jour avec succès !");
    } else {
        // Création
        globalAgents.unshift(nouvelAgent);
        afficherToast("Nouvel agent enregistré !");
    }

    toggleModal(false);
    rafraichirToutesLesVues(globalAgents);
}

function editerAgent(agent) {
    document.getElementById('agent_id').value = agent.id;
    document.getElementById('type_profil').value = agent.role || 'AGENT';
    document.getElementById('matricule').value = agent.matricule;
    document.getElementById('nom_prenom').value = agent.nom_prenom;
    document.getElementById('sexe').value = agent.sexe || 'M';
    document.getElementById('numero_piece').value = agent.numero_piece || '';
    document.getElementById('numero_wave').value = agent.numero_wave || '';
    document.getElementById('site_nom').value = agent.site_nom || '';
    document.getElementById('salaire').value = agent.salaire || 0;
    document.getElementById('photo_url').value = agent.photo_url || '';
    document.getElementById('statut').value = agent.statut || 'Actif';

    document.getElementById('modalFormTitle').textContent = "Modifier l'Agent";
    toggleModal(true);
}


// =============================================================
// 4. RECHERCHE & FILTRES EN TEMPS RÉEL
// =============================================================

function filtrerTableau() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const role = document.getElementById('filterRole').value;
    const statut = document.getElementById('filterStatut').value;

    const resultats = globalAgents.filter(a => {
        const matchText = (a.nom_prenom + a.matricule + a.numero_piece + a.numero_wave + a.site_nom).toLowerCase().includes(query);
        const matchRole = !role || a.role === role;
        const matchStatut = !statut || a.statut === statut;

        return matchText && matchRole && matchStatut;
    });

    afficherTableauGlobal(resultats);
}

function getStatutBadgeClass(statut) {
    switch (statut) {
        case 'Actif': return 'badge-actif';
        case 'Inactif': return 'badge-inactif';
        case 'Suspendu': return 'badge-suspendu';
        case 'Rupture de contrat': return 'badge-rupture';
        default: return 'badge-actif';
    }
}

function afficherToast(message, isError = false) {
    const toast = document.getElementById('toast');
    const toastMsg = document.getElementById('toastMsg');
    const toastIcon = document.getElementById('toastIcon');

    if (!toast || !toastMsg) return;

    toastMsg.textContent = message;
    toastIcon.className = isError ? "fa-solid fa-triangle-exclamation text-red-500" : "fa-solid fa-circle-check text-amber-500";
    
    toast.classList.remove('translate-x-full');
    setTimeout(() => toast.classList.add('translate-x-full'), 3500);
}
    </script>
    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-between items-center">
    <button onclick="imprimerFicheAgentActuel()" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold rounded-lg text-sm flex items-center gap-2 shadow">
        <i class="fa-solid fa-file-pdf"></i> Imprimer Fiche Agent
    </button>
    <button onclick="toggleProfileModal(false)" class="px-5 py-2 bg-slate-900 text-white font-semibold rounded-lg text-sm hover:bg-slate-800 transition">
        Fermer
    </button>
</div>
    <div id="pdfPrintTemplate" style="position: absolute; left: -9999px; top: -9999px; width: 800px; background: #ffffff; color: #0f172a; font-family: sans-serif; padding: 25px;">
</div>
</body>
</html>