<?php
// config.php - Configuration de la base de données et des constantes
define('DB_HOST', 'localhost');
define('DB_NAME', 'mayas_sgbd_db');
define('DB_USER', 'root');
define('DB_PASS', '');

function getPDO() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion DB: " . $e->getMessage());
    }
}

// API ROUTER (Traitement des requêtes AJAX)
if (isset($_GET['api_action'])) {
    header('Content-Type: application/json');
    $pdo = getPDO();
    $action = $_GET['api_action'];

    if ($action === 'get_agents') {
        $stmt = $pdo->query("SELECT * FROM agents ORDER BY id DESC");
        echo json_encode($stmt->fetchAll());
        exit;
    }

    if ($action === 'get_equipements') {
        $stmt = $pdo->query("SELECT * FROM equipements ORDER BY designation ASC");
        echo json_encode($stmt->fetchAll());
        exit;
    }

    if ($action === 'add_agent') {
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("INSERT INTO agents (matricule, type_profil, nom_prenom, sexe, numero_piece, numero_wave, site_nom, salaire, statut, date_debut) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([
            $data['matricule'], $data['type_profil'], $data['nom_prenom'], $data['sexe'], 
            $data['numero_piece'] ?? null, $data['numero_wave'], $data['site_nom'] ?? 'Non Affecté', 
            $data['salaire'], $data['statut'], $data['date_debut']
        ]);
        echo json_encode(['success' => $success]);
        exit;
    }

    if ($action === 'attribuer_equipement') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Obtenir la durée de vie de l'équipement
        $stmtE = $pdo->prepare("SELECT duree_vie_mois FROM equipements WHERE id = ?");
        $stmtE->execute([$data['equipement_id']]);
        $eq = $stmtE->fetch();
        $duree = $eq['duree_vie_mois'] ?? 12;

        $dateAttr = new DateTime($data['date_attribution']);
        $dateRenouv = $dateAttr->modify("+$duree months")->format('Y-m-d');

        $stmt = $pdo->prepare("INSERT INTO attributions_equipements (agent_id, equipement_id, quantite_attribuee, date_attribution, date_renouvellement_prevue, signature_data) VALUES (?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([
            $data['agent_id'], $data['equipement_id'], $data['quantite'], 
            $data['date_attribution'], $dateRenouv, $data['signature_data'] ?? null
        ]);

        // Mise à jour du stock
        $pdo->prepare("UPDATE equipements SET quantite_stock = quantite_stock - ? WHERE id = ?")
            ->execute([$data['quantite'], $data['equipement_id']]);

        echo json_encode(['success' => $success]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGBD Web - MAYAS GROUP / CHEPITIMAYAS</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SignaturePad JS -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <style>
        .badge-actif { background-color: #DEF7EC; color: #03543F; }
        .badge-inactif { background-color: #E1EFFE; color: #1E429F; }
        .badge-suspendu { background-color: #FEECDC; color: #B43403; }
        .badge-rupture { background-color: #FDE8E8; color: #9B1C1C; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        <!-- BARRE LATÉRALE (SIDEBAR) -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col justify-between hidden md:flex">
            <div>
                <div class="p-5 text-center border-b border-slate-800">
                    <h1 class="text-xl font-bold tracking-wider text-amber-500">CHEPITIMAYAS SECURITE</h1>
                    <p class="text-xs text-slate-400">Base Données des superviseurs et agents</p>
                </div>
                <nav class="mt-6 px-4 space-y-2">
                    <button onclick="changerVue('dashboard')" id="nav-dashboard" class="w-full flex items-center px-4 py-3 bg-amber-500 text-slate-900 font-semibold rounded-lg shadow">
                        <i class="fa-solid fa-chart-line w-6"></i> Dashboard
                    </button>
                    <button onclick="changerVue('agents')" id="nav-agents" class="w-full flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 rounded-lg transition">
                        <i class="fa-solid fa-user-shield w-6"></i> Gestion Agents
                    </button>
                    <button onclick="changerVue('materiel')" id="nav-materiel" class="w-full flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 rounded-lg transition">
                        <i class="fa-solid fa-boxes-packing w-6"></i> Suivi Matériel (EPI)
                    </button>
                    <button onclick="changerVue('sites')" id="nav-sites" class="w-full flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 rounded-lg transition">
                        <i class="fa-solid fa-building w-6"></i> Sites & Clients
                    </button>
                    <button onclick="changerVue('prepaie')" id="nav-prepaie" class="w-full flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 rounded-lg transition">
                        <i class="fa-solid fa-file-invoice-dollar w-6"></i> Pré-Paie & Wave
                    </button>
                    <button onclick="changerVue('audit')" id="nav-audit" class="w-full flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 rounded-lg transition">
                        <i class="fa-solid fa-shield-halved w-6"></i> Audit & Logs
                    </button>
                </nav>
            </div>
            <div class="p-4 border-t border-slate-800 text-xs text-slate-500 text-center">
                © 2026 MAYAS GROUP v2.0
            </div>
        </aside>

        <!-- CONTENU PRINCIPAL -->
        <div class="flex-1 flex flex-col overflow-y-auto">

            <!-- EN-TÊTE -->
            <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <button class="md:hidden text-slate-700 text-xl"><i class="fa-solid fa-bars"></i></button>
                    <h2 class="text-xl font-bold text-slate-800" id="page-title">Gestion des Agents & Supervision</h2>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <i class="fa-regular fa-bell text-slate-600 text-xl cursor-pointer"></i>
                        <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5">3</span>
                    </div>
                    <div class="flex items-center space-x-3 border-l pl-6">
                        <div class="w-9 h-9 rounded-full bg-slate-900 text-amber-500 flex items-center justify-center font-bold">
                            AG
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">Admin MAYAS</p>
                            <p class="text-xs text-slate-500">Superviseur RH</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- CONTENU DE LA PAGE -->
            <main class="p-6 space-y-6">

                <!-- SECTION : DASHBOARD / AGENTS -->
                <div id="vue-agents" class="space-y-6">
                    <!-- CARTE STATISTIQUES / KPI -->
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
                            <p class="text-sm font-medium text-slate-500">EPI à Renouveler (30j)</p>
                            <p class="text-2xl font-bold text-amber-600 mt-1" id="stat-expirations">0</p>
                        </div>
                    </div>

                    <!-- BARRE D'ACTIONS & EXPORTS -->
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-wrap gap-3 justify-between items-center">
                        <div class="flex gap-2">
                            <button onclick="toggleModalAgent(true)" class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                                <i class="fa-solid fa-plus"></i> Nouvel Agent / Superviseur
                            </button>
                            <button onclick="toggleModalAttribution(true)" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                                <i class="fa-solid fa-key"></i> Attribuer Matériel
                            </button>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <button onclick="exporterWave()" class="bg-sky-600 hover:bg-sky-700 text-white px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                                <i class="fa-solid fa-mobile-screen-button"></i> Export Wave (.CSV)
                            </button>
                            <button onclick="exporterParSite()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                                <i class="fa-solid fa-building-user"></i> Export par Site
                            </button>
                            <button onclick="exporterGlobal()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                                <i class="fa-solid fa-file-excel"></i> Export Global (.CSV)
                            </button>
                        </div>
                    </div>

                    <!-- RECHERCHE ET FILTRES MULTI-CHAMPS -->
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-wrap gap-4 items-center">
                        <div class="flex-1 min-w-[200px]">
                            <input type="text" id="searchInput" onkeyup="filtrerTableau()" placeholder="Rechercher par nom, matricule, pièce, téléphone, rôle, site..." class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-amber-500">
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

                    <!-- TABLEAU DES AGENTS -->
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
                                <!-- Données injectées dynamiquement -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SECTION : SUIVI MATÉRIEL (EPI) -->
                <div id="vue-materiel" class="space-y-6 hidden">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Inventaire & Gestion des Équipements (EPI)</h3>
                        <p class="text-sm text-slate-500 mb-6">Suivi du matériel en stock, des dotations individuelles et des échéances de renouvellement par agent.</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-slate-50 rounded-lg border">
                                <p class="text-xs text-slate-500 font-semibold uppercase">Tenues & Uniformes</p>
                                <p class="text-xl font-bold text-slate-800 mt-1">45 En Service</p>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-lg border">
                                <p class="text-xs text-slate-500 font-semibold uppercase">Walkies-Talkies VHF</p>
                                <p class="text-xl font-bold text-slate-800 mt-1">12 Actifs</p>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-lg border">
                                <p class="text-xs text-slate-500 font-semibold uppercase">Armes non létales / Bâtons</p>
                                <p class="text-xl font-bold text-slate-800 mt-1">18 En Service</p>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- MODALE 1 : FORMULAIRE D'AJOUT D'AGENT / SUPERVISEUR -->
    <div id="agentModal" class="fixed inset-0 bg-slate-900 bg-opacity-50 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full p-6 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-lg font-bold text-slate-800">Ajouter un Agent ou Superviseur</h3>
                <button onclick="toggleModalAgent(false)" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>

            <form id="agentForm" onsubmit="ajouterAgent(event)" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Type de Profil *</label>
                    <select id="type_profil" onchange="genererMatriculeAuto()" required class="w-full border rounded-lg p-2 text-sm bg-amber-50">
                        <option value="AGENT">Agent (Vigile)</option>
                        <option value="SUPERVISEUR">Superviseur</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Matricule (Auto-incrémenté)</label>
                    <input type="text" id="matricule" readonly required class="w-full border rounded-lg p-2 text-sm bg-slate-100 font-bold text-slate-700">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Nom & Prénoms *</label>
                    <input type="text" id="nom_prenom" required placeholder="Ex: KOUASSI Kouame" class="w-full border rounded-lg p-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Sexe *</label>
                    <select id="sexe" required class="w-full border rounded-lg p-2 text-sm">
                        <option value="M">Masculin (M)</option>
                        <option value="F">Féminin (F)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">N° Pièce (CNI/Passport)</label>
                    <input type="text" id="numero_piece" placeholder="C0012345678" class="w-full border rounded-lg p-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">N° Paiement Wave *</label>
                    <input type="text" id="numero_wave" required placeholder="0700000000" class="w-full border rounded-lg p-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Site d'affectation *</label>
                    <input type="text" id="site_nom" placeholder="Ex: Siège Plateau" class="w-full border rounded-lg p-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Salaire (FCFA) *</label>
                    <input type="number" id="salaire" required placeholder="100000" class="w-full border rounded-lg p-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Date Début *</label>
                    <input type="date" id="date_debut" required class="w-full border rounded-lg p-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Statut *</label>
                    <select id="statut" required class="w-full border rounded-lg p-2 text-sm">
                        <option value="Actif">Actif</option>
                        <option value="Inactif">Inactif</option>
                        <option value="Suspendu">Suspendu</option>
                        <option value="Rupture de contrat">Rupture de contrat</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="toggleModalAgent(false)" class="px-4 py-2 border rounded-lg text-sm text-slate-600 hover:bg-slate-50">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm hover:bg-slate-800">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODALE 2 : ATTRIBUTION DE MATÉRIEL ET SIGNATURE -->
    <div id="attributionModal" class="fixed inset-0 bg-slate-900 bg-opacity-50 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-xl w-full p-6 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-lg font-bold text-slate-800">Dotation Matériel & Fiche de Décharge</h3>
                <button onclick="toggleModalAttribution(false)" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>

            <form id="attributionForm" onsubmit="sauvegarderAttribution(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Sélectionner l'Agent *</label>
                    <select id="attr_agent_id" required class="w-full border rounded-lg p-2 text-sm">
                        <!-- Options injectées par JS -->
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Équipement à remettre *</label>
                    <select id="attr_equipement_id" required class="w-full border rounded-lg p-2 text-sm">
                        <!-- Options injectées par JS -->
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Quantité *</label>
                        <input type="number" id="attr_quantite" value="1" min="1" required class="w-full border rounded-lg p-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Date de remise *</label>
                        <input type="date" id="attr_date" required class="w-full border rounded-lg p-2 text-sm">
                    </div>
                </div>

                <!-- PAD DE SIGNATURE EN LIGNE -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Signature Numérique de l'Agent *</label>
                    <div class="border rounded-lg bg-slate-50 p-1">
                        <canvas id="signature-pad" class="w-full h-36 bg-white rounded border border-dashed border-slate-300 cursor-crosshair"></canvas>
                    </div>
                    <button type="button" onclick="effacerSignature()" class="mt-1 text-xs text-red-600 hover:underline"><i class="fa-solid fa-eraser"></i> Effacer la signature</button>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="toggleModalAttribution(false)" class="px-4 py-2 border rounded-lg text-sm text-slate-600 hover:bg-slate-50">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg text-sm hover:bg-amber-700">Valider & Générer Décharge</button>
                </div>
            </form>
        </div>
    </div>

    <!-- LOGIQUE JAVASCRIPT PERFECTIONNÉE -->
    <script>
        let agentsData = [];
        let equipementsData = [];
        let signaturePad = null;

        // Navigation dynamique entre les vues
        function changerVue(vue) {
            document.getElementById('vue-agents').classList.add('hidden');
            document.getElementById('vue-materiel').classList.add('hidden');

            document.querySelectorAll('nav button').forEach(b => {
                b.className = "w-full flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 rounded-lg transition";
            });

            if (vue === 'agents' || vue === 'dashboard') {
                document.getElementById('vue-agents').classList.remove('hidden');
                document.getElementById('nav-dashboard').className = "w-full flex items-center px-4 py-3 bg-amber-500 text-slate-900 font-semibold rounded-lg shadow";
                document.getElementById('page-title').innerText = "Gestion des Agents & Supervision";
            } else if (vue === 'materiel') {
                document.getElementById('vue-materiel').classList.remove('hidden');
                document.getElementById('nav-materiel').className = "w-full flex items-center px-4 py-3 bg-amber-500 text-slate-900 font-semibold rounded-lg shadow";
                document.getElementById('page-title').innerText = "Gestion & Dotation du Matériel (EPI)";
            }
        }

        // Charger les agents depuis le backend PHP
        async function chargerAgents() {
            try {
                const response = await fetch('?api_action=get_agents');
                agentsData = await response.json();
                afficherAgents(agentsData);
                remplirSelectAgents();
            } catch (error) {
                console.error("Erreur de chargement agents:", error);
            }
        }

        // Auto-Incrémentation des Matricules (AGT-M-001 ou SUP-M-001)
        function genererMatriculeAuto() {
            const type = document.getElementById('type_profil').value;
            const prefix = type === 'SUPERVISEUR' ? 'SUP-M-' : 'AGT-M-';

            const existeM = agentsData
                .map(a => a.matricule || '')
                .filter(m => m.startsWith(prefix))
                .map(m => parseInt(m.replace(prefix, ''), 10))
                .filter(num => !isNaN(num));

            const maxNum = existeM.length > 0 ? Math.max(...existeM) : 0;
            const nextNum = String(maxNum + 1).padStart(3, '0');

            document.getElementById('matricule').value = `${prefix}${nextNum}`;
        }

        // Afficher les agents dans le tableau
        function afficherAgents(data = agentsData) {
            const tbody = document.getElementById('agentTableBody');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="9" class="p-4 text-center text-slate-500">Aucune donnée enregistrée.</td></tr>`;
                calculerKPI();
                return;
            }

            data.forEach(agent => {
                let badgeClass = '';
                if (agent.statut === 'Actif') badgeClass = 'badge-actif';
                else if (agent.statut === 'Inactif') badgeClass = 'badge-inactif';
                else if (agent.statut === 'Suspendu') badgeClass = 'badge-suspendu';
                else badgeClass = 'badge-rupture';

                const roleBadge = (agent.matricule && agent.matricule.startsWith('SUP')) 
                    ? `<span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded font-bold">SUPERVISEUR</span>`
                    : `<span class="bg-gray-100 text-gray-800 text-xs px-2 py-0.5 rounded font-bold">AGENT</span>`;

                tbody.innerHTML += `
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 font-semibold text-slate-800">${agent.matricule}</td>
                        <td class="p-4">${roleBadge}</td>
                        <td class="p-4 font-medium">${agent.nom_prenom}</td>
                        <td class="p-4 text-slate-500">${agent.numero_piece || '-'}</td>
                        <td class="p-4 text-slate-600 font-mono">${agent.numero_wave}</td>
                        <td class="p-4">${agent.site_nom || 'Non Affecté'}</td>
                        <td class="p-4 font-semibold">${Number(agent.salaire).toLocaleString()} FCFA</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold ${badgeClass}">
                                ${agent.statut}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <button onclick="supprimerAgent(${agent.id})" class="text-red-500 hover:text-red-700">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            calculerKPI();
        }

        function calculerKPI() {
            document.getElementById('stat-total').innerText = agentsData.length;
            const actifs = agentsData.filter(a => a.statut === 'Actif');
            document.getElementById('stat-actifs').innerText = actifs.length;
            const masseSalariale = actifs.reduce((sum, a) => sum + Number(a.salaire), 0);
            document.getElementById('stat-masse').innerText = masseSalariale.toLocaleString() + ' FCFA';
        }

        // Filtre multi-champs
        function filtrerTableau() {
            const query = document.getElementById('searchInput').value.toLowerCase().trim();
            const statut = document.getElementById('filterStatut').value;

            const resultat = agentsData.filter(agent => {
                const siteNom = agent.site_nom || '';
                const role = agent.matricule?.startsWith('SUP') ? 'superviseur' : 'agent';

                const matchQuery = 
                    (agent.nom_prenom && agent.nom_prenom.toLowerCase().includes(query)) || 
                    (agent.matricule && agent.matricule.toLowerCase().includes(query)) ||
                    (agent.numero_piece && agent.numero_piece.toLowerCase().includes(query)) ||
                    (agent.numero_wave && agent.numero_wave.toLowerCase().includes(query)) ||
                    (siteNom && siteNom.toLowerCase().includes(query)) ||
                    (role.includes(query));

                const matchStatut = statut === '' || agent.statut === statut;
                return matchQuery && matchStatut;
            });

            afficherAgents(resultat);
        }

        // Soumission de formulaire Agent
        async function ajouterAgent(event) {
            event.preventDefault();

            const payload = {
                type_profil: document.getElementById('type_profil').value,
                matricule: document.getElementById('matricule').value,
                nom_prenom: document.getElementById('nom_prenom').value,
                sexe: document.getElementById('sexe').value,
                numero_piece: document.getElementById('numero_piece').value || null,
                numero_wave: document.getElementById('numero_wave').value,
                site_nom: document.getElementById('site_nom').value || 'Non Affecté',
                salaire: Number(document.getElementById('salaire').value),
                statut: document.getElementById('statut').value,
                date_debut: document.getElementById('date_debut').value
            };

            const response = await fetch('?api_action=add_agent', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            if (response.ok) {
                toggleModalAgent(false);
                document.getElementById('agentForm').reset();
                chargerAgents();
            }
        }

        // Remplit les sélecteurs de la modale d'attribution
        function remplirSelectAgents() {
            const select = document.getElementById('attr_agent_id');
            select.innerHTML = '';
            agentsData.forEach(a => {
                select.innerHTML += `<option value="${a.id}">${a.matricule} - ${a.nom_prenom}</option>`;
            });
        }

        // Enregistrer la dotation avec signature
        async function sauvegarderAttribution(e) {
            e.preventDefault();
            if (signaturePad.isEmpty()) {
                alert("Veuillez recueillir la signature de l'agent avant de valider.");
                return;
            }

            const signatureData = signaturePad.toDataURL();
            const payload = {
                agent_id: document.getElementById('attr_agent_id').value,
                equipement_id: document.getElementById('attr_equipement_id').value,
                quantite: document.getElementById('attr_quantite').value,
                date_attribution: document.getElementById('attr_date').value,
                signature_data: signatureData
            };

            const response = await fetch('?api_action=attribuer_equipement', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            if (response.ok) {
                alert("Attribution enregistrée et Fiche de Décharge générée avec succès !");
                toggleModalAttribution(false);
            }
        }

        function effacerSignature() {
            if (signaturePad) signaturePad.clear();
        }

        // Modales
        function toggleModalAgent(show) {
            const modal = document.getElementById('agentModal');
            if (show) {
                genererMatriculeAuto();
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }

        function toggleModalAttribution(show) {
            const modal = document.getElementById('attributionModal');
            if (show) {
                document.getElementById('attr_date').valueAsDate = new Date();
                modal.classList.remove('hidden');
                if (!signaturePad) {
                    const canvas = document.getElementById('signature-pad');
                    signaturePad = new SignaturePad(canvas);
                }
            } else {
                modal.classList.add('hidden');
            }
        }

        // Exporter Wave CSV
        function telechargerCSV(filename, content) {
            const blob = new Blob(["\ufeff" + content], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.setAttribute("download", filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function exporterGlobal() {
            let csv = "Matricule;Nom_Prenom;Numero_Piece;Numero_Wave;Site;Salaire;Statut\n";
            agentsData.forEach(a => {
                csv += `"${a.matricule}";"${a.nom_prenom}";"${a.numero_piece || ''}";"${a.numero_wave}";"${a.site_nom}";${a.salaire};"${a.statut}"\n`;
            });
            telechargerCSV("Export_Global_MAYAS.csv", csv);
        }

        window.onload = () => {
            chargerAgents();
        };
    </script>
</body>
</html>
