<?php
/**
 * File Upload Handler
 * Handles file uploads with validation and security
 */

class FileUpload {
    /**
     * Upload image file
     */
    public static function uploadImage($file, $subfolder = 'products') {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['success' => false, 'error' => 'No file uploaded'];
        }
        
        // Validate file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
            return ['success' => false, 'error' => 'Invalid file type. Only JPEG, PNG, and WebP are allowed.'];
        }
        
        // Validate file size
        if ($file['size'] > UPLOAD_MAX_SIZE) {
            return ['success' => false, 'error' => 'File too large. Maximum size is ' . (UPLOAD_MAX_SIZE / 1024 / 1024) . 'MB'];
        }
        
        // Create upload directory if it doesn't exist
        $uploadDir = UPLOAD_DIR . '/' . $subfolder;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('img_', true) . '.' . $extension;
        $filepath = $uploadDir . '/' . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $relativePath = 'uploads/' . $subfolder . '/' . $filename;
            return [
                'success' => true,
                'path' => $relativePath,
                'url' => UPLOADS_URL . $subfolder . '/' . $filename
            ];
        }
        
        return ['success' => false, 'error' => 'Failed to upload file'];
    }
    
    /**
     * Delete uploaded file
     */
    public static function deleteFile($path) {
        $fullPath = PUBLIC_PATH . '/' . ltrim($path, '/');
        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }
}

