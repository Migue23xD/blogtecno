<?php
require_once __DIR__ . '/../db/database.php';

class Chat {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getMessages() {
        $stmt = $this->pdo->query("SELECT * FROM chat ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMessage($username, $message, $filePath = null) {
        $stmt = $this->pdo->prepare("INSERT INTO chat (username, message, file_path) VALUES (:username, :message, :file_path)");
        $stmt->execute([
            ':username' => htmlspecialchars($username),
            ':message' => htmlspecialchars($message),
            ':file_path' => $filePath
        ]);
    }
}
?>
