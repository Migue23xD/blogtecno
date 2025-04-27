<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../db/database.php';

$data = json_decode(file_get_contents("php://input"), true);
$articleId = $data['articleId'];
$reactionType = $data['reactionType'];
$userId = $_SESSION['user_id']; // Asume que el usuario está autenticado

try {
    // Insertar o actualizar la reacción del usuario
    $stmt = $pdo->prepare("INSERT INTO article_reactions (article_id, user_id, reaction_type)
                           VALUES (:article_id, :user_id, :reaction_type)
                           ON CONFLICT (article_id, user_id) DO UPDATE SET reaction_type = :reaction_type");
    $stmt->execute([
        ':article_id' => $articleId,
        ':user_id' => $userId,
        ':reaction_type' => $reactionType
    ]);

    // Obtener las reacciones totales
    $stmt = $pdo->prepare("SELECT reaction_type, COUNT(*) as count FROM article_reactions WHERE article_id = :article_id GROUP BY reaction_type");
    $stmt->execute([':article_id' => $articleId]);
    $reactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = ['likes' => 0, 'loves' => 0, 'wows' => 0];
    foreach ($reactions as $reaction) {
        if ($reaction['reaction_type'] === 'like') $response['likes'] = $reaction['count'];
        if ($reaction['reaction_type'] === 'love') $response['loves'] = $reaction['count'];
        if ($reaction['reaction_type'] === 'wow') $response['wows'] = $reaction['count'];
    }

    echo json_encode($response);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al procesar la reacción.']);
}
?>
