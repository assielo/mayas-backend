<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

try {
    // Connexion directe à la base de données MySQL
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=mayas_sgbd_db;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $query = "SELECT 
                agents.id, 
                agents.matricule, 
                agents.nom_prenom, 
                agents.sexe, 
                agents.numero_piece, 
                agents.numero_wave, 
                agents.salaire, 
                agents.statut, 
                sites.nom_site as site_nom 
              FROM agents 
              LEFT JOIN sites ON agents.site_id = sites.id 
              ORDER BY agents.id DESC";

    $stmt = $pdo->query($query);
    $agents = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'data' => $agents
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur BDD : ' . $e->getMessage()
    ]);
}