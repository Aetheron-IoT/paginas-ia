<?php

/* Guardar errores en un log */
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
error_reporting(E_ALL);

$data = json_decode(file_get_contents("php://input"), true);
$userPrompt = $data["prompt"] ?? "";

$apiKey = getenv("GEMINI_API_KEY");

if (!$apiKey) {
    if (file_exists(".env")) {
        $env = parse_ini_file(".env");
        $apiKey = $env["GEMINI_API_KEY"];
    }
}

if (!$apiKey) {
    die("API KEY NO DETECTADA");
}

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent?key=" . $apiKey;

$promptBase = "Devuelve únicamente código HTML5 completo.
No agregues explicaciones.
No uses markdown.
No uses bloques ```html.
" . $userPrompt;

$payload = [
    "contents" => [[
        "parts" => [[ "text" => $promptBase ]]
    ]]
];

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    CURLOPT_POSTFIELDS => json_encode($payload),
]);

$response = curl_exec($ch);

if ($response === false) {
    echo "Error cURL: " . curl_error($ch);
    exit;
}

$result = json_decode($response, true);

$html = $result["candidates"][0]["content"]["parts"][0]["text"] ?? "Error en generación.";

/* Guardar archivo HTML*/

if (!is_dir("pages")) {
    mkdir("pages", 0777, true);
}

$nombreArchivo = "pages/pagina_" . time() . ".html";

file_put_contents($nombreArchivo, $html);

/* -------------------------- */

echo $html;
