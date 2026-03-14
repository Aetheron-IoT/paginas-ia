<?php

$source = null;
$apiKey = null;

// 1️⃣ Intentar desde variable de entorno del sistema (Render o Windows)
$envKey = getenv('GEMINI_API_KEY');

if ($envKey !== false && !empty($envKey)) {
    $apiKey = $envKey;
    $source = "Variable de entorno del sistema (getenv)";
}

// 2️⃣ Si no existe, intentar desde archivo .env
elseif (file_exists(__DIR__ . '/.env')) {
    $envFile = parse_ini_file(__DIR__ . '/.env');
    
    if (!empty($envFile['GEMINI_API_KEY'])) {
        $apiKey = $envFile['GEMINI_API_KEY'];
        $source = "Archivo .env";
    }
}

// 3️⃣ Mostrar resultado
if ($apiKey) {
    echo "<h3>API KEY encontrada</h3>";
    echo "Origen: <strong>$source</strong><br><br>";
    echo "Valor:<br>";
    echo "<pre>" . htmlspecialchars($apiKey) . "</pre>";
} else {
    echo "<h3>No se encontró GEMINI_API_KEY</h3>";
}