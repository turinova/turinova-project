<?php
/**
 * Validation Helper Functions
 */

class Validation {
    
    /**
     * Validate email address
     */
    public static function email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate required field
     */
    public static function required($value) {
        return !empty(trim($value));
    }
    
    /**
     * Validate minimum length
     */
    public static function minLength($value, $min) {
        return strlen(trim($value)) >= $min;
    }
    
    /**
     * Validate maximum length
     */
    public static function maxLength($value, $max) {
        return strlen(trim($value)) <= $max;
    }
    
    /**
     * Validate numeric value
     */
    public static function numeric($value) {
        return is_numeric($value);
    }
    
    /**
     * Validate integer value
     */
    public static function integer($value) {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }
    
    /**
     * Validate positive number
     */
    public static function positive($value) {
        return is_numeric($value) && $value > 0;
    }
    
    /**
     * Sanitize input
     */
    public static function sanitize($input) {
        if (is_array($input)) {
            return array_map([self::class, 'sanitize'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validate date format
     */
    public static function date($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    
    /**
     * Validate file upload
     */
    public static function file($file, $allowedTypes = [], $maxSize = 5242880) {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        
        if ($file['size'] > $maxSize) {
            return false;
        }
        
        if (!empty($allowedTypes)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                return false;
            }
        }
        
        return true;
    }
}
?> 