<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Vérification de la méthode d'envoi
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée. Seul POST est accepté.']);
    exit;
}

// Connexion à la BDD mayas_sgbd_db
$host = '127.0.0.1';
$dbname = 'mayas_sgbd_db';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des données brutes (JSON ou Form-Data)
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    // Validation des champs obligatoires (selon la structure de votre table agents)
    if (
        empty($input['matricule']) || 
        empty($input['nom_prenom']) || 
        empty($input['sexe']) || 
        empty($input['numero_wave']) || 
        empty($input['salaire']) || 
        empty($input['date_debut'])
    ) {
        echo json_encode(['success' => false, 'message' => 'Champs obligatoires manquants.']);
        exit;
    }

    // Préparation de la requête INSERT
    $sql = "INSERT INTO agents (
                matricule, 
                nom_prenom, 
                sexe, 
                numero_piece, 
                numero_wave, 
                salaire, 
                statut, 
                date_debut, 
                site_id, 
                poste_id
            ) VALUES (
                :matricule, 
                :nom_prenom, 
                :sexe, 
                :numero_piece, 
                :numero_wave, 
                :salaire, 
                :statut, 
                :date_debut, 
                :site_id, 
                :poste_id
            )";

    $stmt = $pdo->prepare($sql);

    // Exécution avec sécurisation des valeurs
    $stmt->execute([
        ':matricule'    => trim($input['matricule']),
        ':nom_prenom'   => trim($input['nom_prenom']),
        ':sexe'         => $input['sexe'], // 'M' ou 'F'
        ':numero_piece' => !empty($input['numero_piece']) ? trim($input['numero_piece']) : NULL,
        ':numero_wave'  => trim($input['numero_wave']),
        ':salaire'      => floatval($input['salaire']),
        ':statut'       => $input['statut'] ?? 'Actif',
        ':date_debut'   => $input['date_debut'], // Format YYYY-MM-DD
        ':site_id'      => !empty($input['site_id']) ? intval($input['site_id']) : NULL,
        ':poste_id'     => !empty($input['poste_id']) ? intval($input['poste_id']) : NULL
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Agent créé avec succès !',
        'agent_id' => $pdo->lastInsertId()
    ]);

} catch (PDOException $e) {
    // Gestion des erreurs de doublons (matricule, numéro wave ou pièce déjà existant)
    if ($e->getCode() == 23000) {
        echo json_encode(['success' => false, 'message' => 'Le matricule, la pièce d\'identité ou le numéro Wave existe déjà.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur BDD : ' . $e->getMessage()]);
    }
}
?>