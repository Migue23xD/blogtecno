<?php
require_once __DIR__ . '/../src/db/database.php';

// Manejo de rutas
$requestUri = $_SERVER['REQUEST_URI'];
switch ($requestUri) {
    case '/':
        require_once __DIR__ . '/home.html';
        break;
    case '/login':
        require_once __DIR__ . '/login.html';
        break;
    case '/registro':
        require_once __DIR__ . '/registro.html';
        break;
    case '/chat':
        require_once __DIR__ . '/chat.html';
        break;
    case '/admin':
        require_once __DIR__ . '/admin.html';
        break;
    default:
        http_response_code(404);
        echo "Página no encontrada.";
        break;
}
?>
