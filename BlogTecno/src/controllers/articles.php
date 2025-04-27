<?php
require_once __DIR__ . '/../db/database.php';
require_once __DIR__ . '/../models/Article.php';

$articleModel = new Article($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['category_id'])) {
        $articles = $articleModel->getArticlesByCategory($_GET['category_id']);
        echo json_encode($articles);
    } elseif (isset($_GET['article_id'])) {
        $article = $articleModel->getArticle($_GET['article_id']);
        echo json_encode($article);
    }
}
?>
