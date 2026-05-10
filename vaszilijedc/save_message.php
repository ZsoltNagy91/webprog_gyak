<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        die('Minden kötelező mezőt ki kell tölteni.');
    }

    $sql = "INSERT INTO messages (name, email, subject, message)
            VALUES (:name, :email, :subject, :message)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message
    ]);

    header('Location: contact.php?success=1');
    exit;
}
?>