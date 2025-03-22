<?php
class SessionHelper {
    // Start session if not already started
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return $_SESSION[$key] ?? null;
    }

    public static function destroy() {
        session_destroy();
    }

    // Check if user is logged in
    public static function isLoggedIn() {
        return isset($_SESSION['customer_id']);
    }

    // Check if user is admin
    public static function isAdmin() {
        return self::isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    // Get user role, default is 'guest'
    public static function getRole() {
        return $_SESSION['role'] ?? 'guest';
    }

    // Get user ID
    public static function getUserId() {
        return $_SESSION['customer_id'] ?? null;
    }

    // Check if user has permission for an action
    public static function hasPermission($action) {
        $role = self::getRole();
        
        // Define permissions for each role
        $permissions = [
            'admin' => [
                'view_products', 'add_product', 'edit_product', 'delete_product',
                'view_categories', 'add_category', 'edit_category', 'delete_category',
                'view_orders', 'manage_orders',
                'view_cart', 'checkout'
            ],
            'user' => [
                'view_products', 'view_categories',
                'view_cart', 'checkout'
            ],
            'guest' => [
                'view_products', 'view_categories'
            ]
        ];

        return in_array($action, $permissions[$role] ?? []);
    }

    // Require specific permission or redirect
    public static function requirePermission($action) {
        if (!self::hasPermission($action)) {
            header('Location: /test/Account/login');
            exit;
        }
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /test/Auth/login');
            exit();
        }
    }

    public static function requireAdmin() {
        if (!self::isAdmin()) {
            header('Location: /test/Movie/list');
            exit();
        }
    }

    public static function setCustomer($customer) {
        self::set('customer_id', $customer['customer_id']);
        self::set('email', $customer['email']);
        self::set('first_name', $customer['first_name']);
        self::set('last_name', $customer['last_name']);
        self::set('role', $customer['role']);
    }

    public static function getCustomerInfo() {
        return [
            'customer_id' => self::get('customer_id'),
            'email' => self::get('email'),
            'first_name' => self::get('first_name'),
            'last_name' => self::get('last_name'),
            'role' => self::get('role')
        ];
    }
}
?>