<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 7/11/18
 * Time: 11:40
 */

namespace App\Classes;

use Intervention\Image\ImageManager;

class ImageService
{
    const THUMB_PREFIX = 't_';
    /**
     * Create thumbnails from saved avatar
     *
     * @param string $imageName
     * @param string $path Full path to image
     * @param int $w Thumb weight
     * @param int $h Thumb height
     * @return string
     */
    public function createThumb($imageName, $path, $w = 100, $h = 100)
    {
        $path = public_path($path);
        $imageManager = new ImageManager();
        $thumbName = self::THUMB_PREFIX . $imageName;
        $imageResource = $imageManager->make($path . $imageName);
        $imageResource->fit($w, $h);
        $imageResource->save($path . $thumbName);
        return $thumbName;
    }

    /**
     * Remove image and thumb if they exists
     *
     * @param string $imageName
     * @param string $path
     */
    public function removeImageAndThumb($imageName, $path)
    {
        $basePath = public_path($path);
        $fullPath = $basePath . $imageName;
        if (file_exists($fullPath) && is_file($fullPath)) {
            unlink($fullPath);
        }
        $fullPath = $basePath . self::THUMB_PREFIX . $imageName;
        if (file_exists($fullPath) && is_file($fullPath)) {
            unlink($fullPath);
        }
    }
}