<?php
// core/Auth.php

require_once __DIR__ . '/Database.php';

class Auth {
    
    public static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login($username, $password) {
        self::startSession();
        
        // Use Polyfill wrapper
        $userData = Database::findOne('users', ["username" => $username]);

        if ($userData) {
            // $userData is already an array thanks to wrapper
            if (password_verify($password, $userData['password_hash'])) {
                // Succès
                $_SESSION['user_id'] = $userData['username'];  // ou un UUID si dispo
                $_SESSION['role'] = $userData['role'];
                $_SESSION['logged_in'] = true;
                
                // Redirection selon rôle
                if ($userData['role'] === 'admin') {
                     return ["success" => true, "redirect" => "/web_pro/index.php?page=admin_dashboard"];
                } else {
                     return ["success" => true, "redirect" => "/web_pro/index.php?page=student_dashboard"];
                }
            }
        }

        return ["success" => false, "message" => "Identifiants incorrects."];
    }

    public static function logout() {
        self::startSession();
        session_destroy();
        header("Location: /web_pro/index.php?page=login");
        exit;
    }

    public static function check() {
        self::startSession();
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header("Location: /web_pro/index.php?page=login");
            exit;
        }
    }
    
    public static function isAdmin() {
        self::startSession();
        return (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
    }

    // Helper pour créer un user (Admin only ou setup)
    public static function registerUser($username, $password, $role) {
        $data = [
            "username" => $username,
            "password_hash" => password_hash($password, PASSWORD_DEFAULT),
            "role" => $role,
            "created_at" => date('c')
        ];
        
        return Database::insert('users', $data);
    }
}
?>
