<?php
header("Content-Type: text/html; charset=UTF-8");

$host = "localhost";
$dbname = "blogtecno";
$user = "postgres";
$password = "tu_contraseña";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT * FROM chat ORDER BY created_at DESC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<div class='chat-message'>";
        echo "<p><strong>" . htmlspecialchars($row['username']) . ":</strong> " . htmlspecialchars($row['message']) . "</p>";
        if ($row['file_path']) {
            echo "<p><a href='src/" . htmlspecialchars($row['file_path']) . "' download>Descargar archivo</a></p>";
        }
        echo "<form action='reply_chat.php' method='post'>";
        echo "<textarea name='reply' rows='2' required></textarea>";
        echo "<input type='hidden' name='chat_id' value='" . $row['id'] . "'>";
        echo "<button type='submit'>Responder</button>";
        echo "</form>";
        echo "</div>";
    }
} catch (PDOException $e) {
    echo "Error al conectar con la base de datos: " . $e->getMessage();
}
?>