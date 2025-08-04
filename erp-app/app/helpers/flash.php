<?php
/**
 * Flash Message Helper
 * 
 * Provides functionality for displaying temporary messages to users
 */

class Flash {
    
    /**
     * Set a flash message
     */
    public static function set($type, $message) {
        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }
        
        $_SESSION['flash_messages'][$type] = $message;
    }
    
    /**
     * Get flash message and remove it
     */
    public static function get($type) {
        if (isset($_SESSION['flash_messages'][$type])) {
            $message = $_SESSION['flash_messages'][$type];
            unset($_SESSION['flash_messages'][$type]);
            return $message;
        }
        return null;
    }
    
    /**
     * Check if flash message exists
     */
    public static function has($type) {
        return isset($_SESSION['flash_messages'][$type]);
    }
    
    /**
     * Get all flash messages
     */
    public static function getAll() {
        if (isset($_SESSION['flash_messages'])) {
            $messages = $_SESSION['flash_messages'];
            unset($_SESSION['flash_messages']);
            return $messages;
        }
        return [];
    }
    
    /**
     * Set success message
     */
    public static function success($message) {
        self::set('success', $message);
    }
    
    /**
     * Set error message
     */
    public static function error($message) {
        self::set('error', $message);
    }
    
    /**
     * Set warning message
     */
    public static function warning($message) {
        self::set('warning', $message);
    }
    
    /**
     * Set info message
     */
    public static function info($message) {
        self::set('info', $message);
    }
    
    /**
     * Display flash messages as HTML
     */
    public static function display() {
        $messages = self::getAll();
        $output = '';
        
        foreach ($messages as $type => $message) {
            $output .= '<div class="alert alert-' . htmlspecialchars($type) . ' alert-dismissible fade show" role="alert">';
            $output .= htmlspecialchars($message);
            $output .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            $output .= '</div>';
        }
        
        return $output;
    }
}
?> 