<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../db/database.php';

$data = json_decode(file_get_contents("php://input"), true);
$articleId = $data['articleId'];
$comment = $data['comment'];
$userId = $_SESSION['user_id']; // Asume que el usuario está autenticado

try {
    // Insertar el comentario
    $stmt = $pdo->prepare("INSERT INTO comments (article_id, user_id, comment) VALUES (:article_id, :user_id, :comment)");
    $stmt->execute([
        ':article_id' => $articleId,
        ':user_id' => $userId,
        ':comment' => htmlspecialchars($comment)
    ]);

    // Obtener información del usuario
    $stmt = $pdo->prepare("SELECT display_name, profile_picture FROM users WHERE id = :user_id");
    $stmt->execute([':user_id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $response = [
        'username' => $user['display_name'],
        'profilePicture' => $user['profile_picture'] ?: 'default-profile.png',
        'comment' => htmlspecialchars($comment),
        'timestamp' => date('Y-m-d H:i:s')
    ];

    echo json_encode($response);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al procesar el comentario.']);
}
?>
