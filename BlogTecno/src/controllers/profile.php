<?php
session_start();
require_once __DIR__ . '/../db/database.php';
require_once __DIR__ . '/../models/User.php';

$userModel = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_SESSION['user_id'];
    $profile = $userModel->getProfile($userId);
    echo json_encode($profile);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $displayName = $_POST['display_name'];
    $bio = $_POST['bio'];
    $profilePicture = null;

    if (!empty($_FILES['profile_picture']['name'])) {
        $targetDir = __DIR__ . '/../../uploads/profiles/';
        $fileName = basename($_FILES['profile_picture']['name']);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
            $profilePicture = 'uploads/profiles/' . $fileName;
        }
    }

    $userModel->updateProfile($userId, $displayName, $bio, $profilePicture);
    header('Location: ../../public/profile.html');
}
?>
