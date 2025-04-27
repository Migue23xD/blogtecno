<?php
require_once __DIR__ . '/../db/database.php';

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($username, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->execute([
            ':username' => htmlspecialchars($username),
            ':password' => $hashedPassword
        ]);
    }

    public function login($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => htmlspecialchars($username)]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function updateProfile($userId, $displayName, $bio, $profilePicture = null) {
        $query = "UPDATE users SET display_name = :display_name, bio = :bio";
        $params = [
            ':display_name' => htmlspecialchars($displayName),
            ':bio' => htmlspecialchars($bio),
            ':user_id' => $userId
        ];

        if ($profilePicture) {
            $query .= ", profile_picture = :profile_picture";
            $params[':profile_picture'] = $profilePicture;
        }

        $query .= " WHERE id = :user_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
    }

    public function getProfile($userId) {
        $stmt = $this->pdo->prepare("SELECT username, display_name, bio, profile_picture FROM users WHERE id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
