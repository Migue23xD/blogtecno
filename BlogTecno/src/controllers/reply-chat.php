<?php
// filepath: c:\Users\Miguel\Downloads\BlogTecno\reply_chat.php

$host = "localhost";
$dbname = "blogtecno";
$user = "postgres";
$password = "tu_contraseña";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $chatId = $_POST["chat_id"];
        $reply = $_POST["reply"];

        $stmt = $pdo->prepare("INSERT INTO chat_replies (chat_id, reply, created_at) VALUES (:chat_id, :reply, NOW())");
        $stmt->execute([":chat_id" => $chatId, ":reply" => $reply]);

        header("Location: admin-chats.html");
    }
} catch (PDOException $e) {
    echo "Error al conectar con la base de datos: " . $e->getMessage();
}
?>