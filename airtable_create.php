<?php
// ------------------ CONFIG ------------------
$apiKey = "patFF0ML0rhmOKEVi.d8c1b0affee20f87592618e998a8fa9d9b703f996981012150ea8b3c3d0fec07"; // Ton token Airtable (PAT)
$baseId = "appyg2ArHE6WvjC8x"; // ID de ta base Airtable
$tableName = "CodesPromo"; // Nom exact de ta table

// Récupération POST
$nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
$numero = isset($_POST['numero']) ? trim($_POST['numero']) : '';
$code = isset($_POST['code']) ? trim($_POST['code']) : '';

if (!$nom || !$numero || !$code) {
    echo json_encode(["status" => "error", "message" => "Champs manquants"]);
    exit;
}

$data = [
    "fields" => [
        "nom" => $nom,
        "numero" => $numero,
        "code" => $code,
        "gains" => 0
    ]
];

$url = "https://api.airtable.com/v0/$baseId/" . rawurlencode($tableName);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Pour debug : écris la réponse brute dans un fichier (optionnel)
// file_put_contents('airtable_response.log', $response);

if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode(["status" => "success", "message" => "Code promo créé avec succès"]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Erreur lors de la création (HTTP $httpCode)",
        "response" => $response
    ]);
}

