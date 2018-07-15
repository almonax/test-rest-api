<?php

namespace App\Classes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadService
{
    private $availableImageMimes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif'
    ];

    /**
     * Save uploaded image to destination path
     *
     * @param Request $request
     * @param string $destinationPath
     * @param string $fieldName
     * @return null|string
     */
    public function saveImage(Request $request, $destinationPath, $fieldName)
    {
        $basePath = public_path($destinationPath);
        if ($request->hasFile($fieldName)) {
            $this->revisionPath($basePath);
            $avatar = $request->file($fieldName);
            $imageName = sha1(random_bytes(20)) . '.' . $avatar->extension();
            $avatar->move($basePath, $imageName);
            return $imageName;
        }
        return null;
    }

    /**
     * Save image from /tmp/ directory. Used for PUT requests
     *
     * @param $path
     * @param $destinationPath
     * @return string|null
     */
    public function saveImageFromTmp($path, $destinationPath)
    {
        if (file_exists($path) && is_file($path)) {
            $basePath = public_path($destinationPath);
            $this->revisionPath($basePath);
            $info = getimagesize($path);
            $ext = isset($this->availableImageMimes[$info['mime']])
                ? $this->availableImageMimes[$info['mime']]
                : $this->availableImageMimes['image/jpeg'];
            $imageName = sha1(random_bytes(20)) . '.' . $ext;
            file_put_contents($basePath . $imageName, file_get_contents($path));
            return $imageName;
        }
        return null;
    }

    /**
     * Create directories on required path
     *
     * @param string $path
     * @param int $chmod
     */
    private function revisionPath($path, $chmod = 755)
    {
        if (!file_exists($path)) {
            mkdir($path, $chmod, true);
        }
    }
}