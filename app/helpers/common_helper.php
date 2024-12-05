<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

if (!function_exists('check_auth')) {
    function check_auth() {
        $CI =& lava_instance();
        if (!isset($_SESSION['user_id'])) {
            redirect(''); // Redirect to login page (root route)
            exit();
        }
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('set_flash_alert')) {
    function set_flash_alert($type, $message) {
        $_SESSION['alert'] = [
            'type' => $type,
            'message' => $message
        ];
    }
}

if (!function_exists('get_flash_alert')) {
    function get_flash_alert() {
        if (isset($_SESSION['alert'])) {
            $alert = $_SESSION['alert'];
            unset($_SESSION['alert']);
            return $alert;
        }
        return null;
    }
}
