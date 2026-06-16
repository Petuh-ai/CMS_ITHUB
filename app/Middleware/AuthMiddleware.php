<?php
/**
 * Middleware аутентификации
 */

class AuthMiddleware
{
    public static function checkAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public static function checkGuest()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /admin/dashboard');
            exit;
        }
    }

    public static function checkRole($requiredRoles)
    {
        if (!isset($_SESSION['user_role'])) {
            header('Location: /login');
            exit;
        }

        if (!in_array($_SESSION['user_role'], (array)$requiredRoles)) {
            http_response_code(403);
            die('Доступ запрещен');
        }
    }

    public static function getCurrentUser()
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'],
            'email' => $_SESSION['user_email'],
            'name' => $_SESSION['user_name'],
            'role' => $_SESSION['user_role'],
        ];
    }
}
