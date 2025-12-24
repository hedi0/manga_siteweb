<?php
session_start();
require 'DbConnection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: contact.php");
    exit;
}

// Validate inputs
$required = ['name', 'email', 'subject', 'message'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        header("Location: contact.php?error=Please fill in all fields");
        exit;
    }
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$subject = trim($_POST['subject']);
$message = trim($_POST['message']);
$user_Iduser = isset($_POST['user_Iduser']) ? (int)$_POST['user_Iduser'] : null;

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: contact.php?error=Invalid email address");
    exit;
}

try {
    $stmt = $conn->prepare("
        INSERT INTO contact (name, email, subject, message, user_Iduser)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$name, $email, $subject, $message, $user_Iduser]);
    
    header("Location: contact.php?success=1");
    exit;
    
} catch (PDOException $e) {
    header("Location: contact.php?error=Database error: " . urlencode($e->getMessage()));
    exit;
}
?>