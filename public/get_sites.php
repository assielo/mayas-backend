<?php
header('Content-Type: application/json');

// Incluez votre fichier de connexion existant si vous en avez un (ex: require_once 'config.php';)
// Sinon, configurez la connexion ci-dessous :
$host = 'localhost';
$db   = 'votre_nom_de_bdd';
$user = 'votre_utilisateur';
$pass = 'votre_mot_de_passe';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Adaptez le nom des colonnes selon votre table 'site' (ex: nom_site ou nom)
    $stmt = $pdo->prepare("SELECT id, nom_site FROM site ORDER BY nom_site ASC");
    $stmt->execute();
    $sites = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $sites
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur SQL : ' . $e->getMessage()
    ]);
}
?>