<?php
// En-têtes pour éviter les blocages CORS et forcer le JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = '127.0.0.1';
$db   = 'mayas_sgbd_db';
$user = 'root';
$pass = ''; // Mettez votre mot de passe MySQL si défini

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur connexion BDD : ' . $e->getMessage()]);
    exit();
}

$action = $_GET['action'] ?? 'agents';

if ($action === 'agents') {
    try {
        $stmt = $pdo->query("SELECT * FROM agents ORDER BY id DESC");
        $agents = $stmt->fetchAll();
        echo json_encode($agents);
    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur SQL : ' . $e->getMessage()]);
    }
    exit();
}