<?php
// ------------------ CONFIG ------------------
$apiKey = "patFF0ML0rhmOKEVi.d8c1b0affee20f87592618e998a8fa9d9b703f996981012150ea8b3c3d0fec07"; // Ton token Airtable (PAT)
$baseId = "appyg2ArHE6WvjC8x"; // ID de ta base Airtable
$tableName = "CodesPromo"; // Nom exact de ta table

// --------------------------------------------

// Récupération des données envoyées par le formulaire
$input = json_decode(file_get_contents("php://input"), true);

// Vérification des champs requis
if (!$input || !isset($input["nom"]) || !isset($input["numero"]) || !isset($input["code"])) {
    http_response_code(400);
    echo json_encode(["error" => "Données manquantes"]);
    exit;
}

// Préparation des données pour Airtable
$data = [
    "fields" => [
        "Nom" => $input["nom"],
        "Numero" => $input["numero"],
        "Code" => $input["code"],
        "Gains" => 0 // Valeur par défaut
    ]
];

// Envoi vers Airtable
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.airtable.com/v0/$baseId/" . urlencode($tableName));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Vérification du résultat
if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode(["success" => true]);
} else {
    http_response_code($httpCode);
    echo json_encode(["error" => "Erreur API", "details" => json_decode($response, true)]);
}
