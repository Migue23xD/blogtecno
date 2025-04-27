<?php
require_once __DIR__ . '/../db/database.php';

class Article {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addReaction($articleId, $userId, $reactionType) {
        $stmt = $this->pdo->prepare("INSERT INTO article_reactions (article_id, user_id, reaction_type) VALUES (:article_id, :user_id, :reaction_type)");
        $stmt->execute([
            ':article_id' => $articleId,
            ':user_id' => $userId,
            ':reaction_type' => $reactionType
        ]);
    }

    public function getReactions($articleId) {
        $stmt = $this->pdo->prepare("SELECT reaction_type, COUNT(*) as count FROM article_reactions WHERE article_id = :article_id GROUP BY reaction_type");
        $stmt->execute([':article_id' => $articleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticlesByCategory($categoryId) {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE category_id = :category_id ORDER BY created_at DESC");
        $stmt->execute([':category_id' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticle($articleId) {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id = :article_id");
        $stmt->execute([':article_id' => $articleId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addArticle($title, $content, $categoryId, $authorId) {
        $stmt = $this->pdo->prepare("INSERT INTO articles (title, content, category_id, author_id) VALUES (:title, :content, :category_id, :author_id)");
        $stmt->execute([
            ':title' => htmlspecialchars($title),
            ':content' => htmlspecialchars($content),
            ':category_id' => $categoryId,
            ':author_id' => $authorId
        ]);
    }
}
?>
