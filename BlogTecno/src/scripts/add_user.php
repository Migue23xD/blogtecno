<?php
require_once __DIR__ . '/../db/database.php';

$username = 'nuevo_usuario'; // Cambia esto por el nombre del usuario
$password = 'contraseña_segura'; // Cambia esto por la contraseña deseada
$role = 'user'; // Cambia esto a 'admin' si el usuario debe ser administrador

try {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
    $stmt->execute([
        ':username' => htmlspecialchars($username),
        ':password' => $hashedPassword,
        ':role' => $role
    ]);
    echo "Usuario agregado exitosamente.";
} catch (PDOException $e) {
    echo "Error al agregar el usuario: " . $e->getMessage();
}
?>
