<?php
header('Content-Type: application/json');

// --- CONFIG ---
$apiKey = "patFF0ML0rhmOKEVi.d8c1b0affee20f87592618e998a8fa9d9b703f996981012150ea8b3c3d0fec07";      // Remplace par ta clé Airtable (PAT)
$baseId = "appyg2ArHE6WvjC8x";      // Remplace par l'ID de ta base Airtable
$tableName = "CodesPromo";           // Nom de ta table Airtable
// -------------

// Récupération des données POST
$nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
$numero = isset($_POST['numero']) ? trim($_POST['numero']) : '';

if (!$nom || !$numero) {
    echo json_encode(["status" => "error", "message" => "Nom ou numéro manquant"]);
    exit;
}

// Préparation filtre Airtable (attention aux apostrophes)
$nom_safe = str_replace("'", "\\'", $nom);
$numero_safe = str_replace("'", "\\'", $numero);
$filterFormula = "AND({nom}='$nom_safe', {numero}='$numero_safe')";

$url = "https://api.airtable.com/v0/$baseId/" . urlencode($tableName) . "?filterByFormula=" . urlencode($filterFormula);

// Initialisation cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode >= 200 && $httpCode < 300) {
    $data = json_decode($response, true);
    if (isset($data['records']) && count($data['records']) > 0) {
        $user = $data['records'][0]['fields'];
        echo json_encode([
            "status" => "success",
            "nom" => $user['nom'] ?? '',
            "gains" => $user['gains'] ?? 0,
            "code" => $user['code'] ?? ''
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Nom ou numéro incorrect"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Erreur serveur Airtable", "httpCode" => $httpCode]);
}
