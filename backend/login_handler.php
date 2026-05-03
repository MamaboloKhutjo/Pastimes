<?php
session_start();
require_once 'DBConn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $role = $_POST['role'];

    $stmt = $conn->prepare("SELECT * FROM tblUser WHERE email = ? AND role = ? AND verified = 1");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($user['password_hash'] === $password) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['role'] = $user['role'];
            header("Location: ../frontend/home.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid password.";
            header("Location: ../frontend/login.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Account not found or not verified.";
        header("Location: ../frontend/login.php");
        exit();
    }
}