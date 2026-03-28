<?php

namespace App\Services;

class MediaService
{
    private static $uploadDir = __DIR__ . '/../../interface_frontend/ressources/images/uploads/';

    public static function upload($file, $subDir = '')
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $targetDir = self::$uploadDir . $subDir;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $targetPath = $targetDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return '/img/uploads/' . ($subDir ? $subDir . '/' : '') . $filename;
        }

        return null;
    }

    public static function delete($path)
    {
        $relativePath = str_replace('/img/uploads/', '', $path);
        $fullPath = self::$uploadDir . $relativePath;
        
        if (file_exists($fullPath) && is_file($fullPath)) {
            unlink($fullPath);
        }
    }
}
